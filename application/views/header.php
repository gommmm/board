<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <!-- 기본적인 css -->
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.4/foundation.min.css">
  <link rel="stylesheet" href="<?= CSS ?>font-awesome.min.css" />
  <link rel="stylesheet" href="<?= CSS ?>style.css" />

  <?php if(isset($css) && !empty($css)) :?>
  <?php foreach($css as $url) : ?>
    <link rel="stylesheet" href="<?=$url?>">
  <?php endforeach; ?>
  <?php endif; ?>

  <!-- 기본적인 js -->
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>

  <?php if(isset($js) && !empty($js)) :?>
  <?php foreach($js as $url) : ?>
    <script type="text/javascript" src="<?=$url?>"></script>
  <?php endforeach; ?>
  <?php endif; ?>

</head>
<body>
