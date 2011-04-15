# TBA

The Twitter Busyness Average (TBA) is like [UNIX load average](http://en.wikipedia.org/wiki/Load_%28computing%29), but for people, based on how frequently someone Tweets. The [idea behind the average](https://twitter.com/#!/cdzombak/status/55128208626032640) is that people tend to Tweet less frequently when they're really busy. (This is true for me; it may or may not be true for other people.) For more of a backstory behind this, see [my blog post about it](http://chris.dzombak.name/blog/2011/04/twitter-busyness-average).

## Calculating the Average

This measurement shows how busy somebody was in the last 2 days. The average = (k) / (hourly frequency of Tweets during the last 2 days).

The constant k is unique to each person; this should be adjusted to match the user's Twitter usage pattern. A target value for "standard", not particularly stressful, busyness is around 2. (For [me](http://twitter.com/cdzombak), k ~= 0.9.)

In the future, we might figure out a more formal way to calibrate this for each user; for now, just play with your value of k until you get something that seems right.

To avoid division by zero, if you haven't Tweeted in the last 2 days, calculate the frequency as if you had Tweeted once.

## Example Code

This repo currently contains sample implementations of this algorithm in PHP and in Javascript (as a jQuery plugin). These should be treated as alpha-quality code. They haven't been too thoroughly tested, and they probably lack some error handling and other important things. If you spend any time cleaning them up for real-world use, please fork this repo and send me a pull request!

This algorithm should require sufficiently few requests to the Twitter API that calculating it client-side is appropriate.

There's more detailed documentation in each of the source code files.

## More Fun Stuff

To see how busy [I](http://chris.dzombak.name) am right now, hit [howbusyischris.com](http://howbusyischris.com).

You can also go to `howbusyischris.com/username`, where `username` is your Twitter username. You can further customize that display by passing along a `k` value, like [howbusyischris.com/cdzombak?k=0.7](http://howbusyischris.com/cdzombak?k=0.7).

I intentionally misspelled the word "busyness" in the name of this project; I think that something called the "Twitter business average" would be too easily mistaken for something commerce-related.

## Author

This software/algorithm was developed by [Chris Dzombak](http://chris.dzombak.name).
