<?php
require_once(LIBRARIES . 'config.php');

class Database
{
  private mysqli $link;
  private array $where = [];
  private array $whereParams = [];
  public string $prefix;

  public function __construct()
  {
    global $config;
    $db = $config['database'];
    $this->prefix = $db['prefix'] ?? '';
    $this->link = new mysqli(
      $db['host'],
      $db['username'],
      $db['password'],
      $db['dbname'],
      $db['port'] ?? 3306
    );
    if ($this->link->connect_errno) {
      die('DB Connect Error: ' . $this->link->connect_error);
    }
    $this->link->set_charset($db['charset'] ?? 'utf8mb4');
  }

  public function rawQuery(string $sql, array $params = [])
  {
    $sql = $this->parsePrefix($sql);
    $stmt = $this->link->prepare($sql);
    if (!$stmt) {
      throw new Exception($this->link->error . ' | SQL: ' . $sql);
    }
    if ($params) {
      $types = str_repeat('s', count($params));
      $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    if ($stmt->result_metadata()) {
      $result = $stmt->get_result();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [
      'affected_rows' => $stmt->affected_rows,
      'insert_id'     => $stmt->insert_id
    ];
  }

  public function rawQueryOne(string $sql, array $params = [])
  {
    $rows = $this->rawQuery($sql, $params);
    return is_array($rows) && isset($rows[0]) ? $rows[0] : null;
  }

  public function rawQueryValue(string $sql, array $params = [])
  {
    $row = $this->rawQueryOne($sql, $params);
    return $row ? array_shift($row) : null;
  }

  public function where(string $field, $value, string $operator = '=')
  {
    $this->where[] = "`$field` $operator ?";
    $this->whereParams[] = $value;
    return $this;
  }

  private function buildWhere(): string
  {
    return $this->where ? ' WHERE ' . implode(' AND ', $this->where) : '';
  }

  private function resetWhere()
  {
    $this->where = [];
    $this->whereParams = [];
  }

  public function insert(string $table, array $data)
  {
    if (!$data) return false;
    $fields = array_keys($data);
    $params = array_values($data);
    $sql = "INSERT INTO {$this->prefix}$table (`" . implode('`,`', $fields) . "`)
                VALUES (" . rtrim(str_repeat('?,', count($fields)), ',') . ")";
    $stmt = $this->link->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $this->link->insert_id;
  }

  public function update(string $table, array $data)
  {
    if (!$this->where) {
      throw new Exception('UPDATE without WHERE is not allowed');
    }
    $set = [];
    $params = [];
    foreach ($data as $k => $v) {
      $set[] = "`$k` = ?";
      $params[] = $v;
    }
    $sql = "UPDATE {$this->prefix}$table SET " . implode(',', $set);
    $sql .= $this->buildWhere();
    $params = array_merge($params, $this->whereParams);
    $stmt = $this->link->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $this->resetWhere();
    return $affected;
  }

  public function delete(string $table)
  {
    if (!$this->where) {
      throw new Exception('DELETE without WHERE is not allowed');
    }
    $sql = "DELETE FROM {$this->prefix}$table" . $this->buildWhere();
    $stmt = $this->link->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($this->whereParams)), ...$this->whereParams);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $this->resetWhere();
    return $affected;
  }

  private function parsePrefix(string $sql): string
  {
    return str_replace('#_', $this->prefix, $sql);
  }

  public function getLastInsertId()
  {
    return $this->link->insert_id;
  }
  public function get(string $table, ?array $where = null, $cols = '*')
  {
    if (is_array($cols)) {
      $cols = '`' . implode('`,`', $cols) . '`';
    }
    if ($where) {
      foreach ($where as $field => $value) {
        $this->where($field, $value);
      }
    }
    $sql = "SELECT {$cols} FROM {$this->prefix}{$table}";
    $sql .= $this->buildWhere();
    $sql .= " LIMIT 1";
    $stmt = $this->link->prepare($sql);
    if (!$stmt) {
      throw new Exception($this->link->error . ' | SQL: ' . $sql);
    }
    if ($this->whereParams) {
      $stmt->bind_param(
        str_repeat('s', count($this->whereParams)),
        ...$this->whereParams
      );
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $this->resetWhere();
    return $row ?: null;
  }
  public function select($tableOrSql, array $params = [])
  {
    if (stripos(trim($tableOrSql), 'select') === 0) {
      $sql = $this->parsePrefix($tableOrSql);
      $stmt = $this->link->prepare($sql);
      if (!$stmt) {
        throw new Exception($this->link->error . ' | SQL: ' . $sql);
      }
      if ($params) {
        $stmt->bind_param(
          str_repeat('s', count($params)),
          ...$params
        );
      }
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    $sql = "SELECT * FROM {$this->prefix}{$tableOrSql}";
    $sql .= $this->buildWhere();
    $stmt = $this->link->prepare($sql);
    if (!$stmt) {
      throw new Exception($this->link->error . ' | SQL: ' . $sql);
    }
    if ($this->whereParams) {
      $stmt->bind_param(
        str_repeat('s', count($this->whereParams)),
        ...$this->whereParams
      );
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $this->resetWhere();
    return $rows;
  }
}
