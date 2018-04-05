all: base p2p

base:
	docker build -t openthc/p2p-base -f Dockerfile.base .

p2p:
	docker build -t openthc/p2p .

clean:
	docker rmi -f `docker images --quiet openthc/p2p*`