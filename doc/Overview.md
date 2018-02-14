#

## Join a Peer

Send a Signed Request to a Peer

	echo '{ "host": "p2p.$SITE.com" }' | ./bin/cli.php sign > request.txt
	curl -X POST -d request.txt https://p2p.openthc.org/network/peer

## Request from All Peers

	./bin/cli.php find-product $LIC_ID $INV_ID
	./bin/cli.php find-lot $LIC_ID $INV_ID
	./bin/cli.php find-qa $LIC_ID $INV_ID

