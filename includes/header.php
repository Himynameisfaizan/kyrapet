<?php
include ('config/connect.php');
include ('includes/config.php');
$title = $title ?? $site['name'];
$desc  = $desc  ?? 'Premium pure breed puppies in New Delhi. Vet checked and 100% vaccinated.';
$page  = $page  ?? '';
$a = fn($k)=>$page===$k?' active':'';
$aboutGroup = in_array($page,['about','how','reviews'],true);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=h($title)?></title>
<meta name="description" content="<?=h($desc)?>">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://images.unsplash.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div id="prog"></div>
<div class="topbar"><div class="wrap"><span><?=h($site['addr'])?></span>
<span><a href="mailto:<?=h($site['email'])?>"><?=h($site['email'])?></a> &nbsp;|&nbsp; <a href="tel:<?=h($tel)?>"><?=h($site['phone'])?></a></span></div></div>

<header class="nav" id="nav"><div class="wrap">
  <a class="logo" href="index.php" aria-label="Kyra Pet Shop home"><img src="assets/img/logo.png" alt="Kyra Pet Shop"></a>
  <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>
  <nav class="menu" id="menu">
    <a class="<?=$a('home')?>" href="index.php">Home</a>
    <div class="dd-wrap">
      <button class="dd-toggle<?=$aboutGroup?' active':''?>" aria-expanded="false" aria-haspopup="true">About Us</button>
      <div class="dd">
        <a class="<?=$a('about')?>" href="about.php">About Us<small>Our story and values</small></a>
        <a class="<?=$a('how')?>" href="how-it-works.php">How It Works<small>From first hello to puppy at home</small></a>
        <a class="<?=$a('reviews')?>" href="reviews.php">Reviews<small>Google reviews and video stories</small></a>
      </div>
    </div>
    <a class="<?=$a('breeds')?>" href="breeds.php">Breeds</a>
    <a class="<?=$a('gallery')?>" href="gallery.php">Gallery</a>
    <a class="<?=$a('blog')?>" href="blog.php">Blog</a>
    <a class="<?=$a('contact')?>" href="contact.php">Contact</a>
    <a class="btn btn-teal" href="tel:<?=h($tel)?>">Call now</a>
  </nav>
</div></header>