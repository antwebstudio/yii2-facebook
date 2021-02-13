<?php

class InstanceArticleCest
{
    public function _before(UnitTester $I)
    {
    }

    // tests
    public function tryToTest(UnitTester $I)
    {
        $component = Yii::createObject([
            'class' => \ant\facebook\components\InstantArticle::class,
            'accessToken' => '',
            'appId' => '203564824208326',
            'appSecret' => '',
            'pageId' => '101821744651064',
            'rules' => $this->getRule(),
        ]);
        // $html = $this->readUrl('https://thewatcher.com.my/article/1622');
        $html = $this->getHtml();
        $instantArticle = $I->invokeMethod($component, 'transformHtml', [$html]);

        // $I->assertContains('AUTHOR_NAME', $instantArticle->render());
        $I->assertEquals('<!doctype html><html><head><link rel="canonical" href="LINK"/><meta charset="utf-8"/><meta property="op:generator" content="facebook-instant-articles-sdk-php"/><meta property="op:generator:version" content="1.10.0"/><meta property="op:generator:transformer" content="facebook-instant-articles-sdk-php"/><meta property="op:generator:transformer:version" content="1.10.0"/><meta property="op:markup_version" content="v1.0"/></head><body><article><header><figure><img src="COVER_IMG"/></figure><h1><b>HEADING</b></h1><time class="op-published" datetime="2021-01-19T12:46:14+00:00">January 19th, 12:46pm</time><address><a>AUTHOR_NAME</a>AUTHOR_DESCRIPTION</address></header><p>ARTICLE_CONTENT</p><p> </p><p><b>AUTHOR_NAME</b></p><p>AUTHOR_DESCRIPTION</p><footer><aside><p>COPYRIGHT</p></aside></footer></article></body></html>', $instantArticle->render());
        // dd($instantArticle->render());
        // dd($instantArticle->toDOMElement());
    }

    protected function readUrl($url)
    {
        return file_get_contents($url);
    }

    protected function getRule()
    {
        // return '{"generator_name":"facebook-instant-articles-builder","generator_version":"0.2.2","rules":[{"class":"TextNodeRule"},{"class":"BlockquoteRule","selector":"blockquote"},{"class":"ParagraphRule","selector":"p"},{"class":"H2Rule","selector":"h2"},{"class":"H1Rule","selector":"h1"},{"class":"ListElementRule","selector":"ol, ul"},{"class":"ListItemRule","selector":"li"},{"class":"AnchorRule","selector":"a","properties":{"anchor.href":{"attribute":"href","selector":"a","type":"string"}}},{"class":"ItalicRule","selector":"i"},{"class":"EmphasizedRule","selector":"em"},{"class":"BoldRule","selector":"b, strong"},{"class":"ImageRule","selector":"img","properties":{"image.url":{"attribute":"src","selector":"img","type":"string"},"image.caption":{"attribute":"src","selector":"img","type":"element"}}}]}';

        return '{
            "generator_name": "facebook-instant-articles-builder",
            "generator_version": "0.2.0",
            "rules": [
                {
                    "class": "TextNodeRule"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "html"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "head"
                },
                {
                    "class": "IgnoreRule",
                    "selector" : "meta"
                },
                {
                    "class": "IgnoreRule",
                    "selector" : "noscript"
                },
                {
                    "class": "IgnoreRule",
                    "selector" : "style"
                },
                {
                    "class": "IgnoreRule",
                    "selector" : "script"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "body"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "header"
                },
                {
                    "class": "FooterRule",
                    "selector" : "footer"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "span"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "div"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "section"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "h7"
                },
                {
                    "class": "PassThroughRule",
                    "selector" : "h4"
                },
                {
                    "class": "ListElementRule",
                    "selector" : "ol"
                },
                {
                    "class": "ListElementRule",
                    "selector" : "ul"
                },
                {
                    "class": "ListItemRule",
                    "selector" : "li"
                },
                {
                    "class": "ItalicRule",
                    "selector" : "i"
                },
                {
                    "class": "BoldRule",
                    "selector" : "b"
                },
                {
                    "class": "ParagraphRule",
                    "selector" : "p"
                },
                {
                    "class": "GlobalRule",
                    "selector": ".ia-article",
                    "properties": {
                        "article.title": {
                            "attribute": "content",
                            "selector": ".ia-article-title",
                            "type": "element"
                        },
                        "article.publish": {
                            "format": "Y年m月d日 H:i:s",
                            "selector": ".ia-article-datetime",
                            "type": "date"
                        },
                        "author.name" : {
                            "selector" : ".ia-author .ia-author-name",
                            "type" : "string"
                        },
                        "author.description" : {
                            "selector" : ".ia-author .ia-author-desc",
                            "type" : "string"
                        },
                        "image.url": {
                            "attribute": "src",
                            "selector": ".ia-article-cover img",
                            "type": "string"
                        },
                        "article.body": {
                            "attribute": "content",
                            "selector": ".ia-article-body",
                            "type": "element"
                        },
                        "article.canonical": {
                            "attribute": "data-ia-link",
                            "selector": "[data-ia-link]",
                            "type": "string"
                        }
                    }
                },
                {
                    "class": "IgnoreRule",
                    "selector" : ".ia-ignore"
                },
                {
                    "class": "ParagraphFooterRule",
                    "selector": ".ia-credit",
                    "type": "string"
                }
            ]
        }';
    }

    protected function getHtml()
    {
        return '
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8" />
    <title>TITLE</title>
</head>

<body class="system module-cms controller-entry action-view">
    <div class="clearfix">
        <section>
            <div class="layout-right-sidebar container">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="ia-article" data-ia-link="LINK">
                            <div class="ia-article-cover mobile-fullwidth overlapped-container">
                                <img src="COVER_IMG" alt="" />
                            </div>

                            <h1 class="ia-article-title mt-30 mb-15"><b>HEADING</b></h1>
                            <div class="ia-article-datetime color-lite-black mb-2"><b>2021年01月19日<span style="display: none; ">12:46:14</span></b></div>

                            <div class="ia-article-body cms-content">
                                <p>ARTICLE_CONTENT</p>
                                <p class="d-none">&nbsp;</p>
                                <p class="d-none"><b>AUTHOR_NAME</b></p>
                                <p class="d-none">AUTHOR_DESCRIPTION</p>
                            </div>

                            <p class="mt-3 text-center share-like box">EXTRA_CONTENT</p>

                            <div class="float-left-right text-center mt-40 mt-sm-20">

                                <ul class="tags mb-30 list-li-mr-5 list-a-plr-15 list-a-ptb-7 list-a-bg-white  list-btm-border-white  list-a-br-2 list-a-hvr-primary ">
                                    <li><a href="">TAG1</a></li>
                                    <li><a href="">TAG2</a></li>
                                </ul>
                                <ul class="float-right ml-30 mb-30 list-a-bg-grey list-a-hw-radial-35 list-a-hvr-primary list-li-ml-5">
                                    <li class="mr-10 ml-0">Share</li>
                                    <li>
                                        <i class="ion-social-facebook"></i>
                                    </li>
                                    <li><i class="ion-social-twitter"></i></li>

                                </ul>

                                <ul class="float-right mb-30 list-a-bg-grey list-a-hw-radial-35 list-a-hvr-primary list-li-ml-5">
                                    <li class="mr-10 ml-0">Follow</li>
                                    <li><i class="ion-social-facebook"></i></li>

                                </ul>

                            </div><!-- float-left-right -->

                            <div class="brdr-ash-1 opacty-5"></div>

                            <div class="d-block d-lg-none">
                                <div class="p-3 brder-black ">
                                    <div class="row">
                                        <div class="col-sm-4 col-md-3 col-lg-4 mb-2 mb-sm-auto cms-author">
                                            <img src="AUTHOR_IMG" />
                                        </div>
                                        <div class="col-sm-8 col-md-9 col-lg-8">
                                            <b class="author-name"><a class="ia-author-name" href="AUTHOR_URL">AUTHOR_NAME</a></b>
                                            <div class="ia-author-desc author-desc">AUTHOR_DESCRIPTION</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="comments">
                            <div class="widget-comments">
                                <h4 class=" u-headdress3 pt-20 pb-30">
                                    <span class="u-headdress3__head plr-10"><b>COMMENTS</b></span>
                                </h4>
                                <div id="w0" class="list-view">
                                    <div class="empty">NO DATA</div>
                                </div>
                                <h4 class=" u-headdress3 pt-20 pb-30">
                                    <span class="u-headdress3__head plr-10"><b>TITLE</b></span>
                                </h4>
                                <div data-comment-form="">
                                    <div class="mb-3 text-center box">
                                        <p class="ia-ignore">REGISTER_OR_LOGIN</p>
                                        <a class="btn btn-dark btn-sm" href="/login">LOGIN</a>
                                        <a class="btn btn-dark btn-sm" href="/register">REGISTER</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4">
                        <div class="d-none d-lg-block">
                            <div class="p-3 brder-black ">
                                <div class="row ia-article ia-author">
                                    <div class="col-sm-4 col-md-3 col-lg-4 mb-2 mb-sm-auto cms-author">
                                        <img src="AUTHOR_IMG" />
                                    </div>
                                    <div class="col-sm-8 col-md-9 col-lg-8">
                                        <b class="author-name"><a class="ia-author-name" href="AUTHOR_URL">AUTHOR_NAME</a></b>
                                        <div class="ia-author-desc author-desc">AUTHOR_DESCRIPTION</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="bg-primary color-ccc">
        <h7 class="ia-credit">COPYRIGHT</h7>
    </footer>
</body>

</html>

        ';
    }
}
