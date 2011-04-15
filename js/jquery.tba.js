/**
 * Twitter Busyness Average jQuery plugin
 * by Chris Dzombak <http://chris.dzombak.name>
 * 
 * http://chris.dzombak.name/blog/2011/04/twitter-busyness-average
 *
 * This is alpha-quality code.
 *
 * TODO:
 *   - error handling
 *   - document this code + readme
 *   - licensing
 */

(function($) {
	$.tba = function(s){
	
		var opts = {
			callback:  function(a) {},
			username:  ["cdzombak"], // [string]
			k:         0.75,         // [float]
			precision: 2,            // [int]
			days:      2             // [int]
		}; if(s) $.extend(opts, s);
		
		var tweet_count = 0;
		var now = new Date();
		var current_page = 1;
		
		function get_date(date_str) {
			// from tweet.js:
			// The non-search twitter APIs return inconsistently-formatted dates, which Date.parse
			// cannot handle in IE. We therefore perform the following transformation:
			// "Wed Apr 29 08:53:31 +0000 2009" => "Wed, Apr 29 2009 08:53:31 +0000"
			return new Date(date_str.replace(/^([a-z]{3})( [a-z]{3} \d\d?)(.*)( \d{4})$/i, '$1,$2$4$3'));
		}
		
		function build_url(screen_name, page) {
			return 'http://api.twitter.com/1/statuses/user_timeline.json?callback=?&page=' + page + '&screen_name=' + screen_name;
		}
		
		function twitter_request() {
			$.getJSON( build_url(opts.username, current_page), {}, json_callback );
		}
		
		function calc_tba() {
			var a = opts.k / ( (tweet_count == 0 ? 1 : tweet_count) / (opts.days*24));
			a = a.toFixed(opts.precision);
			opts.callback(a);
		}
		
		function json_callback(result) {
			hit_boundary = false;
			
			for (i=0; i<result.length; i++) {
				tweet_date = get_date(result[i].created_at);
				// 2 days * 24 hours/day * 3600 seconds/hr * 1000 ms/sec
				if ( (now.getTime() - tweet_date.getTime()) < (opts.days * 24 * 3600 * 1000) ) {
					tweet_count++;
				} else {
					hit_boundary = true;
					break;
				}
			}
			
			if (!hit_boundary) {
				current_page++;
				twitter_request();
			} else {
				calc_tba();
			}
		}
		
		twitter_request();
		return this;
	
	};
})(jQuery);
