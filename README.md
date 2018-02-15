# Peer-to-Peer Service for Cannabis Software

A very simple Slim/PHP application to service P2P Communications

This application is implemented as a service that your cannabis software would operate to communicate with other cannabis software.
It provides a method for peer discovery, trust establishment, key exchange and data exchange.

## Installing

It should be quite simple, PHP is like, everywhere man.

	git clone ./
	composer update
	cp ./etc/apache2.conf /etc/apache2/apache2.conf

More information is in the INSTALL.md file.
	
## Peering

You, can peer https://p2p.openthc.org/ or directly with any known peer.

As a "Node0", p2p.openthc.org does some company and service validation before adding you to list of registered providers.
The list is published to https://p2p.openthc.org/network and follows the same specification that any of the other peers would.
Peers are all free to implement their own methods for establishing trust and verifying other peers.

From that connection, you can discover the wider network.

## Establishing Trust

Exchange your secret key with a Peer you wish to trust, this can be done out of band, we recommend using Keybase.
Once the secret is shared you must sign all your requests.

## Signing Requests

Simply use your hashed key with a hash of a canonical request.

	HASH=hash_hmac('sha256', $data, $secret);

	GET /license/M123456/product/987654A
	date: $DATE
	host: $HOST
	x-openthc-peer: 

You have now joined this peer, and all requests to this peer must be signed with your $SECRET as an HMAC.

## Requesting Information

Once registered you fetch the providers list to expand your peer group.
When your software or users need to interact with another system, you simply ask them for details through the common API.

## Publishing Information

This service will repspond to the base Network layer data of the system.
Each provider would implement their own interfaces in this application to interact with their own APIs or database.
