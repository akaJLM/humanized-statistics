=== Humanized statistics ===

Contributors: KwarK
Donate link: http://kwark.allwebtuts.net/
Tags: statistics users, statistics by page, statistics by categories, statistics for home page, wordpress plugin, Google chart tools, Api google Chart
Tested up to: 3.4.2
Stable tag: 0.3

Recolt datas by post and page with post_meta and display it with the Api Google chart on each post and page

== Description ==

Recolt datas by post with post_meta and display it with the Api Google chart on each post and page in your administration. 2 pages admin exists for home page and by categories in dashboard > statistics menu but these pages work with simple addition of post_meta and only directly displaying in administration (no recolted datas for home page and categories).

If you don't view the meta box under each post and page, open one post and one page and in the right corner hit "screen options". Select the meta boxes from the plugin.

In the futur, maybe the plugin creates some humanized and dynamics things (like - by user - "your prefered categories", "your visited categories" and some other kinds of this kind of things in some widgets). Also cron task to create survey by post/by page to make comparison. Also one option for adding personal referers. Some resetting option. Some of this kind of build is already under construction.

Currently, the plugin requests only if is not your home page and the time to creates all the datas is ~250 ms. The plugin have an option to decrease request by user like 1/2 or 1/5 or 1/10 users, etc...

I think ~250ms it is too much. When my skill is up to date too with other technical with wordpress I will make some update.
If it's difficult to decrease these 250ms, The solution profitable for these 250ms is to create more result in administration, more dynamics widgets and more comparison with more survey option (by cron task) to make profitable these 250ms of loading time.

== Installation ==

1. Upload 'humanized-statistics' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Define some input in dashboard > Statistics > general configuration
4. If you don't view the meta box under each post and page, open one post and one page and in the right corner hit "screen options". Select the meta boxes from the plugin.


== Screenshots ==

1. No screenshot actually

== Frequently Asked Questions ==

View forum support on Wordpress for more information


== Upgrade Notice ==

1. Use the Wordpress automatic upgrade notice or upgrade this plugin manually


== Changelog ==

= 0.3 =

* To stay realistic, now the plugin does not make comparison on the latest performance value from one navigator (robots always wins...). Now the plugin register simply the latest value and displays it.

= 0.2 =

* Exclude the only one user_meta from the divisor option...

= 0.1 =

* Original review