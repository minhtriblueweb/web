<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?= $data_seo['title'] ?? $web_name ?>",
    "url": "<?= BASE ?>",
    "sameAs": [<?= implode(", ", array_map(fn($link) => '"' . $link . '"', $sameAs)) ?>]
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "<?= $address ?>",
      "addressRegion": "Ho Chi Minh",
      "postalCode": "70000",
      "addressCountry": "vi"
    }
  }
</script>
