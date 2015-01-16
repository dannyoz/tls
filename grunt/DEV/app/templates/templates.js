.run(["$templateCache", function($templateCache) {  'use strict';

  $templateCache.put('footer.html',
    "<div class=container><ul><li><a href=#>About</a></li><li><a href=#>Archive</a></li><li><a href=#>Faqs</a></li><li><a href=#>Terms &amp; Conditions</a></li><li><a href=#>How to advertise</a></li><li><a href=#>Classifieds</a></li><li><a href=#>Contact us</a></li><li><a href=#>Facebook</a></li><li><a href=#>Twitter</a></li></ul><p class=copyright>Copyright &copy; The Times Literary Supplement Limited 2014. The Times Literary Supplement Limited: 1 London Bridge Street, London SE1 9GF. Registered in England.<br>Company registration number: 935240. VAT no: GB 243 8054 69.</p></div>"
  );


  $templateCache.put('header.html',
    "<div id=header-top class=grid-row><div class=container><div id=brand><h1 id=logo>TLS</h1><p class=sub>The times Literary supplement</p><p class=strap>The leading international weekly for literary culture</p></div><div id=user class=centre-y><button>Subcribe</button> <button class=clear>Login</button></div></div></div><nav><div class=container><div class=grid-row><ul class=futura><li><a href=#>Explore</a></li><li><a href=#>Editions</a></li><li><a href=#>What's New</a></li></ul><div class=search><label ng-if=\"size == 'desktop'\">Search:</label><input type=search placeholder=\"Tls archive, blogs and website\"></div></div></div></nav>"
  );


  $templateCache.put('tls-accordian-column.html',
    "<div class=accordian-column><div class=\"accordian-item card-flat\" ng-repeat=\"item in items\"><div class=accordian-title ng-click=toggleOpen($index); ng-class={open:item.isOpen}><h3 class=futura ng-bind=item.section></h3><div class=toggler><i class=\"icon icon-plus transition-2\" ng-if=!item.isOpen></i> <i class=\"icon icon-minus transition-2\" ng-if=item.isOpen></i></div></div><div class=accordian-body ng-class={open:item.isOpen}><div class=edition-item ng-repeat=\"post in item.posts\"><div class=padded><p class=title-small>{{post.author}}</p><h4><a href=#>{{post.title}}</a></h4></div></div></div></div></div>"
  );


  $templateCache.put('tls-accordian.html',
    "<div class=accordian><div class=accordian-item ng-repeat=\"item in items\"><div class=accordian-title ng-click=toggleOpen($index); ng-class={open:item.isOpen}><h3 class=futura ng-bind=item.heading></h3><div class=toggler><span ng-if=!item.isOpen>Open</span> <span ng-if=item.isOpen>Close</span> <i class=\"icon icon-plus transition-2\" ng-class={open:item.isOpen}></i></div></div><div class=\"accordian-body transition-2\" ng-class={open:item.isOpen} ng-bind-html=item.content></div></div></div>"
  );


  $templateCache.put('tls-card.html',
    "<div ng-if=\"data.type == 'blog'\"><div class=\"blog-item card\" ng-repeat=\"blog in data\"><h3 class=futura><a href=#>Blog</a></h3><div class=\"grid-row padded\"><div class=blog-avatar><a href=#><img class=\"max circular\" src=\"http://placehold.it/90x90\"></a></div><div class=blog-data><div class=inner><h4><a href={{blog.link}}>{{blog.title}}</a></h4><p class=futura><a href=#>{{blog.author}}</a></p><p ng-bind-html=blog.text></p></div></div></div></div></div><div class=card ng-if=\"data.type == 'article' && data.title != null\" ng-class=\"{private:data.visibility == 'private'}\"><h3 class=futura><a ng-attr-href={{data.section.link}} ng-if=data.section.name ng-bind-html=data.section.name></a> <i ng-if=\"data.visibility == 'private'\" class=\"icon icon-key\"></i></h3><img class=max ng-if=data.image_url ng-attr-src=\"{{data.image_url}}\"><div class=padded><h4><a ng-if=data.url ng-attr-href={{data.url}} ng-bind-html=data.title></a></h4><p ng-bind-html=data.excerpt></p></div><footer><p ng-if=data.author class=futura ng-bind=data.author></p></footer></div><div class=card ng-if=\"data.type == 'listen_blog' && data.soundcloud != ''\"><h3 class=futura><a href=#>{{data.section.name}}</a></h3><div class=padded><div class=embed ng-bind-html=sce.trustAsHtml(data.soundcloud);></div><h4><a ng-attr-href={{data.link}} ng-bind-html=data.title></a></h4><p ng-bind-html=data.text></p></div></div><div class=card ng-if=\"data.type == 'mpu'\"><div data-ng-dfp-ad=advert1></div></div>"
  );


  $templateCache.put('edition-preview.html',
    "<div id=edition-preview><div class=container><div id=this-week><div class=\"preview grid-row\"><div class=top><h3>This<br>week's<br>TLS</h3></div><div class=prevbody><div class=grid-6><h4 class=main>Lorem ipsum dolor sit.</h4><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam veritatis amet placeat.</p><button>View edition</button></div><div class=grid-6><img class=max src=http://placehold.it/320x400></div></div></div></div><div id=next-week><div class=\"preview grid-row\"><div class=top><h3>In next<br>week's<br>TLS</h3><div class=date><span><u>OUT</u></span><br><span>12th Nov 2014</span></div></div><div class=prevbody><ul><li><h4>Lorem ipsum.</h4><h5>Lorem ipsum dolor sit amet.</h5></li><li><h4>Adipisci, exercitationem.</h4><h5>Lorem ipsum dolor sit amet.</h5></li><li><h4>Ea, corporis.</h4><h5>Lorem ipsum dolor sit amet.</h5></li><li><h4>Animi, perspiciatis?</h4><h5>Lorem ipsum dolor sit amet.</h5></li></ul></div></div></div></div></div>"
  );


  $templateCache.put('tls-loading.html',
    "<div id=loading ng-if=visible><div class=centre><div class=flipper><div class=flip><img ng-attr-src=\"/wp-content/themes/tls/images/{{currChar}}.png\"></div></div></div></div>"
  );


  $templateCache.put('article.html',
    "<article class=single-post ng-controller=article><div class=\"container relative\" ng-swipe-right=\"chooseArticle('prev')\" ng-swipe-left=\"chooseArticle('next')\"><div class=article-links><a href=\"\" ng-click=\"chooseArticle('next')\" class=next-article>Lorem ipsum dolor sit.</a> <a href=\"\" ng-click=\"chooseArticle('prev')\" class=prev-article>Lorem ipsum dolor sit amet.</a></div><div class=article-current ng-class={turn:pageTurn}><div class=grid-row><div class=\"grid-6 push-3\"><img class=max src=http://placehold.it/800x392></div></div><div class=grid-row><div class=article-body><h2>Lorem ipsum dolor sit.</h2><h4 class=author>Lorem ipsum.</h4><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><blockquote>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos neque laudantium nemo hic voluptatum illum saepe culpa asperiores dolores.</blockquote><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><img src=http://placehold.it/368x368><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum debitis impedit, in nihil recusandae error inventore tenetur. Aliquid quaerat sequi harum obcaecati dolorum illum non dolore esse, expedita perferendis numquam repellendus ipsa molestiae tempore laborum tenetur unde, iusto veniam suscipit?</p></div></div></div></div></article>"
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