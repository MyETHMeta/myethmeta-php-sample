# php-sample
How to use MyETHMeta from PHP

## Usage

- Install frontend dependencies with `yarn`
- Run the frontend with `yarn start`

- Install backend dependencies with `composer install`
- Set the `SIGNER_ADDRESS` and `SIGNER_PRIVATE_KEY` in `.env`
- Start the server with `./start_dev.sh` or simply `php -S localhost:8080`

- Open the frontend at `http://localhost:1234`
- Connect your MetaMask wallet
- Set the URL, and push the 'Write metadata' button to send the data and the signature to the server, which calls the contract
