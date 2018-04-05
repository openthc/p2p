# Docker

## Configuring the environment

### Environment Parameters

Edit the values of `APP_HOST` and `APP_ROOT` to meet the needs of your container.

The only required value to edit is:

* `APP_HOST`

### SSL Certificates

Before building, copy your **SSL Certificate**, **SSL Certificate Key**, and **Chain File** into the `etc/ssl/` directory in this repository.

Ensure each have the following filename in `etc/ssl/`

* SSL Certificate `host.crt`
* SSL Certificate Key `host.key`
* Chain File `host-chain.pem`

## Building the container images

Use `make` to build the images.

```bash
make all # Build everything
make
make clean # Delete all openthc/p2p images
```

## Running the container images locally

```bash
# Start a vanilla p2p instance
$ docker run --name openthc-p2p -p 4443:443 -it openthc/p2p 

# Start p2p and detach the process
$ docker run -d --name openthc-p2p -p 4443:443 -it openthc/p2p 

# Shell into a new p2p container
#   Note: Daemon services will not be running
$ docker run --name openthc-p2p -p 4443:443 -it openthc/p2p bash

# Shell into an already running p2p container
$ docker exec -it openthc-php bash

# Stop a detached p2p container
$ docker stop openthc-p2p
```
