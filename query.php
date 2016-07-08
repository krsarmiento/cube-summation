<?php 

class Query {
	private $result = array();
	private $dimension = 4;
	private $queries = "
UPDATE 2 2 2 1
QUERY 1 1 1 1 1 1
QUERY 1 1 1 2 2 2
QUERY 2 2 2 2 2 2";

	private $cube;

	public function execute() {
		$this->cube = array();

		foreach (range(1, $this->dimension) as $x)  {
			foreach (range(1, $this->dimension) as $y)  {
				foreach (range(1, $this->dimension) as $z) {
					$this->cube[$x][$y][$z] = 0;
				}
			}
		}

		return $this->parse();
	}


	public function parse(){
		$commands = explode("\n", $this->queries);
		foreach ($commands as $command){
			$params = explode(' ', $command);
			$args = array_slice($params, 1);
			if ($params[0] == 'QUERY') {
				$this->result[] = $this->makeQuery($args);
			}else if($params[0] == 'UPDATE') {
				$this->makeUpdate( $args );
			}
		}

		return $this->result;
	}

	public function makeQuery($args){
		$result = 0;
		$coord1 = array(
			'x' => $args[0],
			'y' => $args[1],
			'z' => $args[2]
		);
		$coord2 = array(
			'x' => $args[3],
			'y' => $args[4],
			'z' => $args[5]
		);

		foreach (range(1, $this->dimension) as $x)  {
			foreach (range(1, $this->dimension) as $y)  {
				foreach (range(1, $this->dimension) as $z) {
					$xValid = $x >= $coord1['x'] && $x <= $coord2['x'];
					$yValid = $y >= $coord1['y'] && $x <= $coord2['y'];
					$zValid = $z >= $coord1['z'] && $x <= $coord2['z'];
					if( $xValid && $yValid && $zValid) {
						$result += $this->cube[$x][$y][$z];
					}
				}
			}
		}

		return $result;
	}

	public function makeUpdate($args){
		$this->cube[ $args[0] ][ $args[1] ][ $args[2] ] = intval($args[3]);
	}
}

$q = new Query();
var_dump($q->execute());