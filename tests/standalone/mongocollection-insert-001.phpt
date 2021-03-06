--TEST--
MongoCollection::insert() with ReplicaSet failover.
--DESCRIPTION--
Here we test whether the ping is only done once every 5 seconds.
--SKIPIF--
skip Manual test
--FILE--
<?php
function error_handler($code, $message)
{
	echo $message, "\n";
}

set_error_handler('error_handler');

MongoLog::setLevel(MongoLog::ALL);
MongoLog::setModule(MongoLog::ALL);

//require_once dirname(__FILE__) . "/../utils.inc";

$mongo = new Mongo("mongodb://%s:%d,%s:%d/?replicaSet=seta");
$mongo->safe = true;
$mongo->setReadPreference(Mongo::RP_NEAREST);

$coll1 = $mongo->selectCollection('phpunit', 'query');
$coll1->drop();

$i = 0;
while ($i < 5) {
	echo "Inserting $i\n";
	try {
		$coll1->insert(array('_id' => $i, 'x' => "foo" . dechex($i)), array('safe' => 1));
	} catch ( Exception $e ) {
		echo get_class( $e ), ': ', $e->getCode(), ', ', $e->getMessage(), "\n";
	}
	$i++;
	sleep(1);
}
?>
--EXPECTF--
PARSE   INFO: Parsing mongodb://%s:%d,%s:%d/?replicaSet=seta
PARSE   INFO: - Found node: %s:%d
PARSE   INFO: - Found node: %s:%d
PARSE   INFO: - Connection type: MULTIPLE
PARSE   INFO: - Found option 'replicaSet': 'seta'
PARSE   INFO: - Switching connection type: REPLSET
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (read)
CON     INFO: connection_create: creating new connection for %s:%d
CON     INFO: get_server_flags: start
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 248
CON     FINE: get_server_flags: setting maxBsonObjectSize to 16777216
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 17
CON     WARN: is_ping: last pinged at %d; time: 0ms
CON     INFO: connection_create: creating new connection for %s:%d
CON     INFO: get_server_flags: start
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 277
CON     FINE: get_server_flags: setting maxBsonObjectSize to 16777216
CON     FINE: get_server_flags: added tag dc:east
CON     FINE: get_server_flags: added tag use:reporting
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 17
CON     WARN: is_ping: last pinged at %d; time: 0ms
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     INFO: ismaster: start
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 248
CON     FINE: ismaster: the server name matches what we thought it'd be (%s:%d).
CON     FINE: ismaster: the found replicaset name matches the expected one (seta).
CON     INFO: ismaster: set name: seta, ismaster: 0, secondary: 0, is_arbiter: 1
CON     INFO: found host: %s:%d
CON     INFO: found host: %s:%d
CON     INFO: ismaster: last ran at %d
CON     INFO: discover_topology: ismaster worked
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     INFO: discover_topology: found new host: %s:%d
CON     INFO: connection_create: creating new connection for %s:%d
CON     INFO: get_server_flags: start
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 278
CON     FINE: get_server_flags: setting maxBsonObjectSize to 16777216
CON     FINE: get_server_flags: added tag dc:west
CON     FINE: get_server_flags: added tag use:accounting
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 17
CON     WARN: is_ping: last pinged at %d; time: 0ms
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     INFO: ismaster: start
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 277
CON     FINE: ismaster: the server name matches what we thought it'd be (%s:%d).
CON     FINE: ismaster: the found replicaset name matches the expected one (seta).
CON     INFO: ismaster: set name: seta, ismaster: 1, secondary: 0, is_arbiter: 0
CON     INFO: found host: %s:%d
CON     INFO: found host: %s:%d
CON     INFO: ismaster: last ran at %d
CON     INFO: discover_topology: ismaster worked
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     INFO: ismaster: start
CON     FINE: send_packet: read from header: 36
CON     FINE: send_packet: data_size: 278
CON     FINE: ismaster: the server name matches what we thought it'd be (%s:%d).
CON     FINE: ismaster: the found replicaset name matches the expected one (seta).
CON     INFO: ismaster: set name: seta, ismaster: 0, secondary: 1, is_arbiter: 0
CON     INFO: found host: %s:%d
CON     INFO: found host: %s:%d
CON     INFO: ismaster: last ran at %d
CON     INFO: discover_topology: ismaster worked
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: forcing primary for command
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (read)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: - connection: type: SECONDARY, socket: 5, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:west
REPLSET FINE:   - tag: use:accounting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: - connection: type: SECONDARY, socket: 5, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:west
REPLSET FINE:   - tag: use:accounting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: - connection: type: SECONDARY, socket: 5, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:west
REPLSET FINE:   - tag: use:accounting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 1
REPLSET INFO: - connection: type: SECONDARY, socket: 5, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:west
REPLSET INFO:   - tag: use:accounting
IO      FINE: getting reply
IO      FINE: getting cursor header
IO      FINE: getting cursor body
Inserting 0
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: forcing primary for getlasterror
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 5
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 15
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
IO      FINE: getting reply
IO      FINE: getting cursor header
IO      FINE: getting cursor body
MongoCursorException: 0, %s:%d: need to login
Inserting 1
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 4
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 4
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 4
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 14
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 14
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 14
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: forcing primary for getlasterror
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 4
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 4
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 4
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 14
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 14
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 14
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
IO      FINE: getting reply
IO      FINE: getting cursor header
IO      FINE: getting cursor body
MongoCursorException: 0, %s:%d: need to login
Inserting 2
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 3
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 3
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 3
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 13
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 13
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 13
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: forcing primary for getlasterror
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 3
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 3
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 3
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 13
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 13
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 13
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
IO      FINE: getting reply
IO      FINE: getting cursor header
IO      FINE: getting cursor body
MongoCursorException: 0, %s:%d: need to login
Inserting 3
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 2
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 2
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 2
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 12
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 12
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 12
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: forcing primary for getlasterror
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 2
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 2
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: %d, time left: 2
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 12
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 12
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: %d, time left: 12
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
IO      FINE: getting reply
IO      FINE: getting cursor header
IO      FINE: getting cursor body
MongoCursorException: 0, %s:%d: need to login
Inserting 4
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: 1349169600, time left: 1
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: 1349169600, time left: 1
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: 1349169600, time left: 1
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: 1349169600, time left: 11
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: 1349169600, time left: 11
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: 1349169600, time left: 11
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
CON     INFO: forcing primary for getlasterror
CON     INFO: mongo_get_read_write_connection: finding a REPLSET connection (write)
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: 1349169600, time left: 1
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: 1349169600, time left: 1
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: is_ping: pinging %s:%d;X;%d
CON     FINE: is_ping: skipping: last ran at %d, now: 1349169600, time left: 1
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: 1349169600, time left: 11
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: 1349169600, time left: 11
CON     FINE: discover_topology: ismaster got skipped
CON     FINE: discover_topology: checking ismaster for %s:%d;X;%d
CON     FINE: found connection %s:%d;X;%d (looking for %s:%d;X;%d)
CON     FINE: ismaster: skipping: last ran at %d, now: 1349169600, time left: 11
CON     FINE: discover_topology: ismaster got skipped
REPLSET FINE: finding candidate servers
REPLSET FINE: - all servers
REPLSET FINE: filter_connections: adding connections:
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: filter_connections: done
REPLSET FINE: sorting servers by priority and ping time
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: sorting servers: done
REPLSET FINE: selecting near servers
REPLSET FINE: selecting near servers: nearest is 0ms
REPLSET FINE: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET FINE:   - tag: dc:east
REPLSET FINE:   - tag: use:reporting
REPLSET FINE: selecting near server: done
REPLSET FINE: pick server: random element 0
REPLSET INFO: - connection: type: PRIMARY, socket: 4, ping: 0, hash: %s:%d;X;%d
REPLSET INFO:   - tag: dc:east
REPLSET INFO:   - tag: use:reporting
IO      FINE: getting reply
IO      FINE: getting cursor header
IO      FINE: getting cursor body
MongoCursorException: 0, %s:%d: need to login

Notice: CON     FINE: mongo_connection_destroy: Closing socket for %s:%d;X;%d. in Unknown on line 0

Notice: CON     INFO: freeing connection %s:%d;X;%d in Unknown on line 0

Notice: CON     FINE: mongo_connection_destroy: Closing socket for %s:%d;X;%d. in Unknown on line 0

Notice: CON     INFO: freeing connection %s:%d;X;%d in Unknown on line 0

Notice: CON     FINE: mongo_connection_destroy: Closing socket for %s:%d;X;%d. in Unknown on line 0

Notice: CON     INFO: freeing connection %s:%d;X;%d in Unknown on line 0
