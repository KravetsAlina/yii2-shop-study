<?php

//в форматиров виде показ параметры масив\обьект
// подключ в web -> index.php
function debug($arr)
{
  echo '<pre>' . print_r($arr, true) . '</pre>';
}
