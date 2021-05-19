<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="<?php echo htmlspecialchars( $author, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
    <meta name="description" content="<?php echo htmlspecialchars( $desc, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
    <title><?php echo htmlspecialchars( $pagetitle, ENT_COMPAT, 'UTF-8', FALSE ); ?></title>
    <link rel="stylesheet" href="/@vime/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/@vime/css/vime.css">
    <link rel="shortcut icon" href="/@vime/images/raven.png" type="image/x-icon">
</head>

<body class="bg-dark">