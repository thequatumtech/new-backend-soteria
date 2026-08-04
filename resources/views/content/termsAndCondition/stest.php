    <?php

class CoinbaseSmartAccount {
    private $rpcUrl;
    private $privateKey;
    private $client;
    
    public function __construct($rpcUrl, $privateKey) {
        $this->rpcUrl = $rpcUrl;
        $this->privateKey = $privateKey;
        $this->client = curl_init();
    }
    
    /**
     * Generate Ethereum address from private key
     */
    private function privateKeyToAddress($privateKey) {
        // Remove 0x prefix if present
        $privateKey = str_replace('0x', '', $privateKey);
        
        // This is a simplified version - in production you'd use a proper crypto library
        // like kornrunner/keccak or web3p/ethereum-lib
        throw new Exception("Please implement proper private key to address conversion using a crypto library");
    }
    
    /**
     * Make RPC call to Base Sepolia
     */
    private function rpcCall($method, $params = []) {
        $data = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => 1
        ];
        
        curl_setopt_array($this->client, [
            CURLOPT_URL => $this->rpcUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($this->client);
        
        if (curl_error($this->client)) {
            throw new Exception('cURL Error: ' . curl_error($this->client));
        }
        
        $decoded = json_decode($response, true);
        
        if (isset($decoded['error'])) {
            throw new Exception('RPC Error: ' . $decoded['error']['message']);
        }
        
        return $decoded['result'];
    }
    
    /**
     * Get account nonce
     */
    public function getNonce($address) {
        return $this->rpcCall('eth_getTransactionCount', [$address, 'latest']);
    }
    
    /**
     * Get gas price
     */
    public function getGasPrice() {
        return $this->rpcCall('eth_gasPrice');
    }
    
    /**
     * Get chain ID (Base Sepolia = 84532)
     */
    public function getChainId() {
        return $this->rpcCall('eth_chainId');
    }
    
    /**
     * Create Coinbase Smart Account
     * Note: This is a simplified version. The actual implementation would require:
     * 1. Proper cryptographic libraries for key operations
     * 2. Smart contract deployment transactions
     * 3. Account abstraction protocol implementation
     */
    public function createSmartAccount() {
        try {
            // Step 1: Get owner address from private key
            // $ownerAddress = $this->privateKeyToAddress($this->privateKey);
            
            // Step 2: Get network information
            $chainId = $this->getChainId();
            $gasPrice = $this->getGasPrice();
            
            echo "Chain ID: " . $chainId . "\n";
            echo "Gas Price: " . $gasPrice . "\n";
            
            // Step 3: Deploy smart account contract
            // This would involve:
            // - Creating deployment transaction
            // - Signing with owner private key
            // - Broadcasting transaction
            
            // Placeholder for smart account creation
            $smartAccountData = [
                'chainId' => $chainId,
                'gasPrice' => $gasPrice,
                'owner' => '0x3db7C592b3527A8a64166C6D2096DAC163EDc34c', // Would be derived from private key
                'status' => 'pending_deployment'
            ];
            
            return $smartAccountData;
            
        } catch (Exception $e) {
            throw new Exception('Failed to create smart account: ' . $e->getMessage());
        }
    }
    
    /**
     * Sign message with private key
     * This is a placeholder - use proper cryptographic library in production
     */
    private function signMessage($message, $privateKey) {
        // Implement proper ECDSA signing using libraries like:
        // - kornrunner/secp256k1
        // - web3p/ethereum-lib
        throw new Exception("Implement proper message signing with crypto library");
    }
    
    /**
     * Send transaction
     */
    public function sendTransaction($transaction) {
        return $this->rpcCall('eth_sendRawTransaction', [$transaction]);
    }
    
    public function __destruct() {
        if ($this->client) {
            curl_close($this->client);
        }
    }
}

// Usage example
try {
    $rpcUrl = "https://api.developer.coinbase.com/rpc/v1/base-sepolia/YOUR_RPC_TOKEN";
    $privateKey = "YOUR_PRIVATE_KEY"; // Without 0x prefix
    
    $smartAccount = new CoinbaseSmartAccount($rpcUrl, $privateKey);
    $result = $smartAccount->createSmartAccount();
    
    echo "Smart Account Creation Result:\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Alternative simplified approach using direct cURL calls
function makeRpcCall($url, $method, $params = []) {
    $data = [
        'jsonrpc' => '2.0',
        'method' => $method,
        'params' => $params,
        'id' => 1
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ]
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Example usage of direct cURL
$rpcUrl = "https://api.developer.coinbase.com/rpc/v1/base-sepolia/YOUR_RPC_TOKEN";

// Get chain ID
$chainResult = makeRpcCall($rpcUrl, 'eth_chainId');
echo "Chain ID: " . $chainResult['result'] . "\n";

// Get latest block
$blockResult = makeRpcCall($rpcUrl, 'eth_blockNumber');
echo "Latest Block: " . $blockResult['result'] . "\n";

?>