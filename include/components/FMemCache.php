<?php
/**
 * memory cache
 *
 */
class FMemCache extends CMemCache
{
    private $_masterServers;
    private $_slaveServers;
    private $_slave;

    public function setMasterServers($servers)
    {
        $this->_masterServers = $this->_parseServers($servers);
        $this->setServers($this->_masterServers);
    }

    public function setSlaveServers($servers)
    {
        $this->_slaveServers = $this->_parseServers($servers);
    }

    public function getSlave()
    {
        if ($this->_slave === null) {
            if ($this->_slaveServers !== null) {
                try {
                    $config = array(
                        'class' => 'CMemCache',
                        'servers' => $this->_slaveServers,
                        'keyPrefix' => $this->keyPrefix
                    );
                    $this->_slave = Yii::createComponent($config);
                    $this->_slave->init();
                } catch (Exception $e) {
                    $this->_slave = false;
                }
            } else {
                $this->_slave = false;
            }
        }

        return $this->_slave;
    }

    public function delete($id)
    {
        $this->getSlave() && $this->getSlave()->delete($id);
        return parent::delete($id);
    }

    private function _parseServers($servers)
    {
        $_servers = array();
        foreach (explode(' ', $servers) as $server) {
            if (!empty($server)) {
                $arr = explode(':', trim($server));
                $_servers[] = array(
                    'host' => $arr[0],
                    'port' => $arr[1]
                );
            }
        }

        return $_servers;
    }
}
