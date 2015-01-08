.run(["$templateCache", function($templateCache) {  'use strict';

  $templateCache.put('footer.html',
    "<div class=container><ul><li><a href=#>About</a></li><li><a href=#>Archive</a></li><li><a href=#>Faqs</a></li><li><a href=#>Terms &amp; Conditions</a></li><li><a href=#>How to advertise</a></li><li><a href=#>Classifieds</a></li><li><a href=#>Contact us</a></li><li><a href=#>Facebook</a></li><li><a href=#>Twitter</a></li></ul><p class=copyright>Copyright &copy; The Times Literary Supplement Limited 2014. The Times Literary Supplement Limited: 1 London Bridge Street, London SE1 9GF. Registered in England.<br>Company registration number: 935240. VAT no: GB 243 8054 69.</p></div>"
  );


  $templateCache.put('header.html',
    "<div id=header-top class=grid-row><div class=container><div id=brand><h1 id=logo>TLS</h1><p class=sub>The times Literary supplement</p><p class=strap>The leading international weekly for literary culture</p></div><div id=user class=centre-y><button>Subcribe</button> <button class=clear>Login</button></div></div></div><nav><div class=container><div class=grid-row><ul class=futura><li><a href=#>Explore</a></li><li><a href=#>Editions</a></li><li><a href=#>What's New</a></li></ul><div class=search><label ng-if=\"size == 'desktop'\">Search:</label><input type=search placeholder=\"Tls archive, blogs and website\"></div></div></div></nav>"
  );


  $templateCache.put('tls-accordian-column.html',
    "<div class=accordian-column><div class=\"accordian-item card-flat\" ng-repeat=\"item in items\"><div class=accordian-title ng-click=toggleOpen($index); ng-class={open:item.isOpen}><h3 class=futura ng-bind=item.section></h3><div class=toggler><i class=\"icon icon-plus transition-2\" ng-class={open:item.isOpen}></i></div></div><div class=accordian-body ng-class={open:item.isOpen}><div class=edition-item ng-repeat=\"post in item.posts\"><div class=padded><p class=title-small>{{post.author}}</p><h4><a href=#>{{post.title}}</a></h4></div></div></div></div></div>"
  );


  $templateCache.put('tls-accordian.html',
    "<div class=accordian><div class=accordian-item ng-repeat=\"item in items\"><div class=accordian-title ng-click=toggleOpen($index); ng-class={open:item.isOpen}><h3 class=futura ng-bind=item.heading></h3><div class=toggler><span ng-if=!item.isOpen>Open</span> <span ng-if=item.isOpen>Close</span> <i class=\"icon icon-plus transition-2\" ng-class={open:item.isOpen}></i></div></div><div class=\"accordian-body transition-2\" ng-class={open:item.isOpen} ng-bind-html=item.content></div></div></div>"
  );


  $templateCache.put('tls-card.html',
    "<div ng-if=\"data.type == 'blog'\"><h3 class=futura><a href=#>Blog</a></h3><div class=\"grid-row padded\"><div class=grid-4><a href=#><img class=\"max circular\" src=\"http://www.placecage.com/c/170/170\"></a></div><div class=\"grid-7 push-1\"><h4><a href=#>{{data.heading}}</a></h4><p><a href=#>{{data.author}}</a></p><p><a href=#>{{data.subheading}}</a></p></div></div></div><div ng-if=\"data.type == 'book'\"><h3 class=futura><a href=#>{{data.category}}</a></h3><div class=\"grid-row padded\"><div class=grid-4><a href=#><img class=\"max circular\" src=\"http://placehold.it/120x120\"></a></div><div class=\"grid-7 push-1\"><h4><a href=#>{{data.heading}}</a></h4><p><a href=#>{{data.author}}</a></p><p><a href=#>{{data.subheading}}</a></p></div></div></div><div ng-if=\"data.type == 'article'\"><h3 class=futura><a href=#>{{data.category}}</a></h3><a href=#><img class=max src=http://placehold.it/380x192></a><div class=padded><h4><a href=#>{{data.heading}}</a></h4><p><a href=#>{{data.excerpt}}</a></p></div><footer><p class=sub><a href=#>{{data.subheading}}</a></p><p class=futura><a href=#>{{data.author}}</a></p></footer></div><div ng-if=\"data.type == 'poem'\"><h3 class=futura><a href=#>{{data.category}}</a></h3><div class=padded><h4><a href=#>{{data.title}}</a></h4><p><a href=#>{{data.excerpt}}</a></p></div><footer><p class=sub><a href=#>{{data.subheading}}</a></p><p class=futura><a href=#>{{data.author}}</a></p></footer></div>"
  );


  $templateCache.put('edition-preview.html',
    "<div id=edition-preview><div class=container><div id=this-week><div class=\"preview grid-row\"><div class=top><h3>This<br>week's<br>TLS</h3></div><div class=prevbody><div class=grid-6><h4 class=main>Lorem ipsum dolor sit.</h4><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam veritatis amet placeat.</p><button>View edition</button></div><div class=grid-6><img class=max src=http://placehold.it/320x400></div></div></div></div><div id=next-week><div class=\"preview grid-row\"><div class=top><h3>In next<br>week's<br>TLS</h3><div class=date><span><u>OUT</u></span><br><span>12th Nov 2014</span></div></div><div class=prevbody><ul><li><h4>Lorem ipsum.</h4><h5>Lorem ipsum dolor sit amet.</h5></li><li><h4>Adipisci, exercitationem.</h4><h5>Lorem ipsum dolor sit amet.</h5></li><li><h4>Ea, corporis.</h4><h5>Lorem ipsum dolor sit amet.</h5></li><li><h4>Animi, perspiciatis?</h4><h5>Lorem ipsum dolor sit amet.</h5></li></ul></div></div></div></div></div>"
  );


  $templateCache.put('tls-loading.html',
    "<!-- <div id=\"loading\" ng-if=\"visible\">\r" +
    "\n" +
    "\t<div class=\"centre\">\r" +
    "\n" +
    "\t\t<ul class=\"fadeIn\">\r" +
    "\n" +
    "\t\t\t<li ng-repeat=\"dot in dots\">\r" +
    "\n" +
    "\t\t\t\t<b ng-attr-style=\"-webkit-animation-delay : {{$index*0.1}}s\"></b>\r" +
    "\n" +
    "\t\t\t</li>\r" +
    "\n" +
    "\t\t</ul>\r" +
    "\n" +
    "\t</div>\r" +
    "\n" +
    "</div> --><div id=loading ng-if=visible><div class=centre><div class=flipper><!-- \t\t\t<div class=\"flip curr-flip\" ng-class=\"{flipping : isFlipping,hori : direction == 'h', vert : direction == 'v'}\">\r" +
    "\n" +
    "\t\t\t\t<img ng-attr-src=\"/wp-content/themes/tls/images/{{currChar}}.png\" />\r" +
    "\n" +
    "\t\t\t</div>\r" +
    "\n" +
    "\t\t\t<div class=\"flip next-flip\" ng-class=\"{flipping : isFlipping,hori : direction == 'h', vert : direction == 'v'}\">\r" +
    "\n" +
    "\t\t\t\t<img ng-attr-src=\"/wp-content/themes/tls/images/{{nextChar}}.png\" />\r" +
    "\n" +
    "\t\t\t</div> --><div class=\"flip test\"><img ng-attr-src=\"/wp-content/themes/tls/images/{{currChar}}.png\"></div></div></div></div>"
  );


  $templateCache.put('article.html',
    "<article class=single-post ng-controller=article><div class=\"container relative\" ng-swipe-right=\"chooseArticle('prev')\" ng-swipe-left=\"chooseArticle('next')\"><div class=article-links><a href=\"\" ng-click=\"chooseArticle('next')\" class=next-article>Lorem ipsum dolor sit.</a> <a href=\"\" ng-click=\"chooseArticle('prev')\" class=prev-article>Lorem ipsum dolor sit amet.</a></div><div class=article-current ng-class={turn:pageTurn}><div class=grid-row><div class=\"grid-6 push-3\"><img class=max src=http://placehold.it/800x392></div></div><div class=grid-row><div class=article-body><h2>Lorem ipsum dolor sit.</h2><h4 class=author>Lorem ipsum.</h4><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><blockquote>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos neque laudantium nemo hic voluptatum illum saepe culpa asperiores dolores.</blockquote><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><img src=http://placehold.it/368x368><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p></div></div></div></div></article>"
  );


  $templateCache.put('home.html',
    "<section id=home ng-controller=home><div id=banner style=background-image:url(/wp-content/themes/tls/images/hero.jpg)><div class=container><div class=caption><p class=category>Memoir</p><h2>The soldier poets</h2><p class=excerpt>Does poetry carry more weight than history in the legacy of the First World War?</p></div></div><div class=gradient></div></div><div class=container><div ng-if=columns tls-columns=columns></div></div><div class=grid-row id=subscriber ng-class={locked:isLocked}><div class=container><h5 class=\"centred-heading grid-row\">Subscriber exclusive</h5><div class=subscribe-grid><div class=card><h3 class=futura>Archive</h3><img class=max src=http://placehold.it/380x192><p class=padded>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div></div><div class=subscribe-grid><div class=card><h3 class=futura>Letters to the editor</h3><img class=max src=http://placehold.it/380x192><p class=padded>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div></div><div class=subscribe-grid><div class=card><h3 class=futura>NB</h3><img class=max src=http://placehold.it/380x192><p class=padded>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div></div><div class=subscribe-grid><div class=card><h3 class=futura>Wall street journal</h3><img class=max src=http://placehold.it/380x192><p class=padded>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div></div></div></div></section>"
  );


  $templateCache.put('tls-column.html',
    "<div class=grid-row ng-if=\"current == 'desktop'\"><div class=grid-4><div class=card tls-card=cards[0]></div><div class=card tls-card=cards[1]></div><div class=card tls-card=cards[2]></div><div class=card tls-card=cards[3]></div></div><div class=grid-4><div class=card tls-card=cards[4]></div><div class=card tls-card=cards[5]></div><div class=card tls-card=cards[6]></div></div><div class=grid-4><div class=card tls-card=cards[5]></div><div class=card tls-card=cards[6]></div><div class=card tls-card=cards[4]></div></div></div><div class=grid-row ng-if=\"current == 'tablet'\"><div class=grid-6><div class=card tls-card=cards[0]></div><div class=card tls-card=cards[1]></div><div class=card tls-card=cards[4]></div><div class=card tls-card=cards[6]></div><div class=card tls-card=cards[4]></div></div><div class=grid-6><div class=card tls-card=cards[2]></div><div class=card tls-card=cards[3]></div><div class=card tls-card=cards[5]></div><div class=card tls-card=cards[4]></div></div></div><div class=grid-row ng-if=\"current == 'mobile'\"><div class=grid-12><div class=card ng-repeat=\"card in cards\" tls-card=cards[$index]></div></div></div>"
  );


  $templateCache.put('latest-editions.html',
    "<section id=latest-edition ng-model=latesteditions></section>"
  );


  $templateCache.put('search.html',
    "<section id=search ng-controller=search><div class=container><div class=grid-row><div class=grid-4><h2>Sort by...</h2><div class=filter-block><h3>Content type</h3></div><div class=filter-block><h3>Date</h3></div><div class=filter-block><h3>Category</h3></div></div><div class=grid-8></div></div></div></section>"
  );


  $templateCache.put('tls-pagination.html',
    "<ul class=pagination><li ng-if=\"config.currentPage>1\"><a ng-click=switchPage(config.currentPage-1)>Prev</a></li><li class=desktop-only ng-if=\"config.currentPage>1\">...</li><li class=desktop-only ng-if=\"config.currentPage-2>0\"><a ng-click=switchPage(config.currentPage-2)>{{config.currentPage-2}}</a></li><li class=desktop-only ng-if=\"config.currentPage-1>0\"><a ng-click=switchPage(config.currentPage-1)>{{config.currentPage-1}}</a></li><li class=current><a ng-click=switchPage(config.currentPage)>{{config.currentPage}}</a></li><li class=desktop-only ng-if=\"(config.currentPage+1) < (config.pageCount+1)\"><a ng-click=switchPage(config.currentPage+1)>{{config.currentPage+1}}</a></li><li class=desktop-only ng-if=\"(config.currentPage+2) < (config.pageCount+1)\"><a ng-click=switchPage(config.currentPage+2)>{{config.currentPage+2}}</a></li><li class=desktop-only ng-if=\"config.currentPage < config.pageCount\">...</li><li ng-if=\"config.currentPage < config.pageCount\"><a ng-click=switchPage(config.currentPage+1)>Next</a></li></ul>"
  );
}])