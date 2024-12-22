import MyEthMetaClient from "myethmeta";

declare global {
    interface Window {
        ethereum?: any;
    }
}

if (!window.ethereum || !window.ethereum.isMetaMask) {
    alert("Please install MetaMask")
}

let client = new MyEthMetaClient();

document.addEventListener("DOMContentLoaded", () => {
    const connectMetamaskButton = document.getElementById("connect-metamask-button");
    const writeMetadataButton = document.getElementById("write-metadata-button");
    const addressMetamask = document.getElementById("address_metamask") as HTMLInputElement;
    const metadataUrlInput = document.getElementById("metadata_url") as HTMLInputElement;

    connectMetamaskButton?.addEventListener("click", async () => {
        if (typeof window.ethereum !== 'undefined') {
            try {
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                const account = accounts[0];
                if (addressMetamask) {
                    addressMetamask.value = account;
                }
            } catch (error) {
                console.error("Error connecting to MetaMask", error);
            }
        } else {
            console.error("MetaMask is not installed");
        }
    });

    writeMetadataButton?.addEventListener("click", async () => {
        const { domain, types, message, metamask_request } = await client.generateDataForSigning(addressMetamask.value, metadataUrlInput.value);
        let signature = await window.ethereum.request({
            "method": "eth_signTypedData_v4",
            "params": [
                addressMetamask.value,
                metamask_request
            ]
        });
        console.log("Signature:", signature);

        // POST the message and the signature in a JSON to index.php
        const response = await fetch('http://localhost:8080/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                signature: signature
            })
        });

        if (response.ok) {
            console.log("Data successfully sent to index.php");
        } else {
            console.error("Failed to send data to index.php");
        }
    });
});
