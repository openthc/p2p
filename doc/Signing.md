# Signing Requests

All requests for information from each peer needs to be signed using an HMAC

## Canonicalize the Request

The canonical method for rolling the request together is simply folding these values together with a new line, trim whitespace and generate an SHA1 hash.
All these values are case sensitive.
$VERB should be in UPPER case, $HOST_SECRET, $PATH and $ARGS_SORTED should be in the natural case on the wire.
$ARGS_SORTED takes the query string arguments, sorts by key and reassembles.
$ARGS_SORTED MUST NOT have key names duplicated, for maximum interoperabality one SHOULD only use scalar data as values.

	$HOST_SECRET
	$VERB
	$PATH
	$ARGS_SORTED

For example:

	GET
	/object/lot/ABC123
	aa=bb&cc=dd&ee=ff

And this folds into

	"GET\n/object/lot/ABC123\naa=bb&cc=dd&ee=ff"

The result of this constructed and hash with sha256 is

	$sig = hmac_sha1($HOST_SECRET, $DATA);

Save this Request-Hash for usage later.

## Create Derived Signing Key


## Sign Request

### See Also

 * http://tools.ietf.org/html/rfc3986
 * https://docs.aws.amazon.com/general/latest/gr/signing_aws_api_requests.html
 * https://blog.andrewhoang.me/how-api-request-signing-works-and-how-to-implement-it-in-nodejs-2/
