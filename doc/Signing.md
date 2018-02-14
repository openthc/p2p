# Signing Requests

All requests for information from each peer needs to be signed.

## Canonicalize the Request

The canonical method for rolling the request together is effectively

	$VERB
	$PATH
	$ARGS
	date: $DATE
	host: $HOST

For example:

	GET
	/license/X999999/log/ABC123

	date: 2017-01-01 18:00:00
	host: p2p.openthc.org

The result of this constructed and hash with sha256 is

	d9d52ca82520e52f7d934f56a7153bfbb085aa4a91acb67a796250e91fc00733

Save this Request-Hash for usage later.

## Create Derived Signing Key


## Sign Request




### See Also

 * http://tools.ietf.org/html/rfc3986
 * https://docs.aws.amazon.com/general/latest/gr/signing_aws_api_requests.html
 * https://blog.andrewhoang.me/how-api-request-signing-works-and-how-to-implement-it-in-nodejs-2/
