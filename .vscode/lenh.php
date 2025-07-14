// gộp 2 bảng vào 1
INSERT INTO tbl_news (
slugvi, slugen, namevi, nameen, descvi, descen, contentvi, contenten,
file, status, numb, views, type, created_at, updated_at
)
SELECT
'', '', -- slugvi, slugen (bỏ trống, có thể generate sau)
namevi, nameen, -- tên tiếng Việt & tiếng Anh
descvi, descen, -- mô tả
'', '', -- contentvi, contenten để trống vì không có trong tbl_tieuchi
file, status, numb, 0, -- views mặc định 0
'tieuchi', -- gán type là tieuchi
created_at, updated_at
FROM tbl_tieuchi;
