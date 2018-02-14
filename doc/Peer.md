# Peer

A Peer communicates with another Peer using signed requests.
The request are signed, HMAC, with a shared secret.
Each pair of Peers should have a different secret.

	PeerA <--> PeerB using SecretC
	PeerA <--> PeerE using SecretF
	PeerB <--> PeerE using SecretG

# Connecting

To exchange secrets a Peer will initiate a connection with another peer.
This first request is signed by your Keybase key.
The receiver can accept this, validate it, and, if accepted, will notify you.
This peer can (and should) also send a unique shared key back.
