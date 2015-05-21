<?php get_header(); ?>

    <section id="latest-edition" ng-controller="latesteditions" ng-cloak>

        <div class="container relative" ng-if="ready" ng-swipe-right="chooseEdition('prev',prev)" ng-swipe-left="chooseEdition('next',next)">

            <!-- Desktop Pagination -->
            <div class="article-links" ng-if="size == 'desktop'">
                <div class="inner">
                    <a ng-click="nextPrevEdition('next', nextEdition.title, nextEdition.url)" ng-if="nextEdition.title != null" class="article-nav next-article">
                        <div class="icon icon-right-arrow"><span ng-bind="nextEdition.title"></span></div>
                    </a>
                    <a ng-click="nextPrevEdition('previous', previousEdition.title, previousEdition.url)" ng-if="previousEdition.title != null" class="article-nav prev-article">
                        <div class="icon icon-left-arrow"><span ng-bind="previousEdition.title"></span></div>
                    </a>
                </div>
            </div>

            <div class="edition-current">

                <h1 ng-if="size == 'desktop'" ng-bind="latestEdition.title"></h1>

                <!-- Latest Edition Top Section -->
                <div class="editions-top grid-row">

                    <div class="editions-top-left featured-col fadeIn">

                        <!-- Tablet / Mobile Pagination -->
                        <h1 ng-if="size != 'desktop'" ng-bind="latestEdition.title"></h1>
                        <div class="article-links" ng-if="size != 'desktop'">
                            <div class="inner">
                                <a ng-click="nextPrevEdition('next', nextEdition.title, nextEdition.url)" ng-if="nextEdition.url" class="article-nav next-article">
                                    <div class="icon icon-right-arrow"><span ng-bind="nextEdition.title"></span></div>
                                </a>
                                <a ng-click="nextPrevEdition('previous', previousEdition.title, previousEdition.url)" ng-if="previousEdition.url" class="article-nav prev-article">
                                    <div class="icon icon-left-arrow"><span ng-bind="previousEdition.title"></span></div>
                                </a>
                            </div>
                        </div>

                        <div class="img-wrapper">
                            <img class="max" ng-attr-src="{{currentEdition.featured.image_url}}">
                        </div>
                        <div class="title-small">No. <span ng-bind="currentEdition.featured.issue_no"></span></div>
                    </div>

                    <div class="editions-top-right fadeIn">
                        <div class="grid-row">
                            <!--Public content -->
                            <div class="public-col">
                                <h2>{{publicObj.title}}</h2>
                                <div class="edition-item" ng-repeat="public in publicObj.articles">
                                    <div class="padded">
                                        <h3 class="futura"><a href="{{public.section.link}}" ng-bind-html="public.section.name"></a></h3>
                                        <p class="title-small" ng-bind="public.author"></p>
                                        <h4><a href="{{public.url}}" ng-bind-html="public.title"></a></h4>
                                    </div>
                                </div>
                            </div>
                            <!--Regular content -->
                            <div class="regular-col">
                                <h2>{{regularsObj.title}}</h2>
                                <div class="edition-item" ng-repeat="regular in regularsObj.articles">
                                    <div class="padded" ng-class="{ 'private' : regular.taxonomy_article_visibility[0].slug == 'private'}">
                                        <h3 class="futura" ng-show="regular.type == 'then_and_now'" ><a href="javascript:void(0)" >Then And Now</a><i class="icon icon-key-after" ng-if="regular.taxonomy_article_visibility[0].slug == 'private'"></i></h3>
                                        <h3 class="futura" ng-hide="regular.type == 'then_and_now'"><a href="{{regular.section.link}}" ng-bind="regular.section.name"></a><i class="icon icon-key-after" ng-if="regular.taxonomy_article_visibility[0].slug == 'private'"></i></h3>
                                        <p class="title-small" ng-bind="regular.author"></p>
                                        <h4><a href="{{regular.url}}" ng-bind="regular.title"></a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Latest Edition Bottom Section -->
        <div ng-if="ready" class="editions-bottom fadeIn">

            <div class="container relative">
                <div class="title-icon icon">
                    <div class="icon-border icon-key"></div>
                    <h2>{{subscribersObj.title}}</h2>
                </div>

                <div class="grid-row" ng-if="size == 'desktop'">

                    <div  class="grid-4" ng-repeat="column in col3">

                        <div class="card-flat" ng-repeat="card in column">
                            <h3 class="futura"><a ng-href="{{card.section.link}}" ng-bind-html="card.section.name"></a></h3>
                            <div class="edition-item" ng-repeat="post in card.posts">
                                <div class="padded">
                                    <p class="title-small">{{::post.author}}</p>
                                    <h4><a ng-href="{{::post.url}}">{{::post.title}}</a></h4>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="grid-row" ng-if="size == 'tablet'">

                    <div  class="grid-6" ng-repeat="column in col2">

                        <div class="card-flat" ng-repeat="card in column">
                            <h3 class="futura"><a ng-href="{{card.section.link}}" ng-bind-html="card.section.name"></a></h3>
                            <div class="edition-item" ng-repeat="post in card.posts">
                                <div class="padded">
                                    <p class="title-small">{{::post.author}}</p>
                                    <h4><a ng-href="{{::post.url}}">{{::post.title}}</a></h4>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="grid-row" ng-if="size == 'mobile'">

                    <div  class="grid-12" ng-repeat="column in col1">
                        <!-- Accordian directive -->
                        <div ng-if="column.length > 0" tls-accordian-column="column"></div>
                    </div>
                </div>

            </div>
        </div>

        <div tls-loading="loading"></div>

    </section>

<?php get_footer(); ?>