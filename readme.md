# Humanized statistics

**Recolt datas by post and page with post_meta and display it with the Api Google chart on each post and page**

keywords: statistics users, statistics by page, statistics by categories, statistics for home page, wordpress plugin, Google chart tools, Api google Chart

* Tested up to: 3.4.2
* Stable tag: 0.6

## Description

Recolt datas by post with post_meta and display it with the Api Google chart on each post and page in your administration. 2 pages admin exists for home page and by categories in dashboard > statistics menu. These pages work with simple addition of post_meta and only directly displaying in administration (no recolted datas for home page and categories on front end).

If you don't view the meta box under each post and page, open one post and one page and in the top-right corner hit "screen options". Select the meta boxes from the plugin.

In the futur, maybe the plugin creates some humanized and dynamics things (like - by user - "your prefered categories",...) because the plugin recolts datas to make that. Also cron task to create survey by post/by page to make comparison. Also one option for adding personal referers. Some manual and automatic resetting option. Some of this kind of build is already under construction.

The plugin have an option to decrease request by user like 1/2 or 1/5 or 1/10 users, etc...

## Installation

1. Upload 'humanized-statistics' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Define some input in dashboard > Statistics > general configuration
4. If you don't view the meta box under each post and page, open one post and one page and in the right corner hit "screen options". Select the meta boxes from the plugin.


## Screenshots

* No screenshot actually

## Frequently Asked Questions

* View forum support on Wordpress for more information

## Upgrade Notice

* Use the Wordpress automatic upgrade notice or upgrade this plugin manually

## Changelog

* 0.6 
> Fixed chart displaying (navigators comparison) for pages/posts in administration.

* 0.5
> Bug correction where the datas collection was stopped.

* 0.4
> Performance improvement. Added option personal referers. Added manual reset options.

* 0.3
> To stay realistic, now the plugin does not make comparison on the latest performance value from one navigator (robots always wins...). Now the plugin register simply the latest value and displays it.

* 0.2
> Exclude the only one user_meta from the divisor option...