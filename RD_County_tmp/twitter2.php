<?php
if ($realtr['gender']=='f') {
?>

 <script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 317,
  height: 70,
  theme: {
    shell: {
      background: '#962496',
      color: '#050305'
    },
    tweets: {
      background: '#fcfcfc',
      color: '#030203',
      links: '#eb076e'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setUser('<? echo $realtr['twitter_account'];?>').start();
</script>
<? } 
else {
 ?>
 <script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 317,
  height: 70,
  theme: {
    shell: {
      background: '#358dbd',
      color: '#140c14'
    },
    tweets: {
      background: '#faf5fa',
      color: '#0a070a',
      links: '#eb0713'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setUser('<? echo $realtr['twitter_account'];?>').start();
</script>

<?
}
?>
