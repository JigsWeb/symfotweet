<?php

namespace SymfoTweet\ClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfoTweetClientBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
