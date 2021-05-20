#!/usr/bin/php
<?PHP
# Funções

# ===============================================
#  Checa se determinado ip se encontra no dentro
#  do intervalo da rede pesquisada
# ===============================================
function checkIpInterval( $IP , $INTERVAL ){

	$IP = explode("." , $IP);
	$INTERVAL[0] = explode("." , $INTERVAL[0]);
	$INTERVAL[1] = explode("." , $INTERVAL[1]);

	# ==============================================
	#  Checa se esta dentro do intervalo inicial
	# ==============================================
	if( $IP[2] >= $INTERVAL[0][2] && $IP[3] >= $INTERVAL[0][3] )
	{

		#echo "DEBUG: Dentro do intervalo inicial.\n";
	
		if( $IP[2] <= $INTERVAL[1][2] && $IP[3] <= $INTERVAL[1][3] )
		{
			#echo "DEBUG: Dentro do intervalo final.\n";
			return True;
		}
		else
		{
			#echo "DEBUG: Fora do intervalo final.\n";
			return False;
		}

	}
}




# ===============================
#  Intervalo de rede
# ===============================
$INTERVALOS = [ ['192.168.64.1' , '192.168.71.254' , "Local1" ],
                ['192.168.79.1' , '192.168.79.254' , "Local2" ],

# Debug
#$IP = "192.168.27.222";

echo "\n";


# =====================================
#   Abrindo o arquivo csv de entrada
$handleIn = fopen("inventario.csv", "r");
$handleOut = fopen("inventario-out.csv", "w");

$header = fgetcsv($handleIn, 2000, ",");

$ipCol = array_search('Endereço IP' , $header);
$lastCol = count($header);


$header[$lastCol] = 'IP Location';

fputcsv($handleOut, $header, ',');

while ($row = fgetcsv($handleIn, 2000, ",")) {


	print_r($header);

	# ==================================================
	#  Percorrendo a lista de intervalos de IP
	# -------------------------------------------------
	foreach($INTERVALOS as $INTERVAL)
	{

		# ==================================================
		#  Executando a função que checa o intervalo de ip
		#  e retorna o nome da loja
		if(checkIpInterval( $row[$ipCol] , $INTERVAL ))
			
		{
			#echo "Econtrado na rede $INTERVAL[2]\n";
			# Adiciona o nome da loja na coluna 
			$row[$lastCol] = $INTERVAL[2];
			break;
		}
		else
		{
			$row[$lastCol] = "not find";
		}


	}

	# =============================================
	#  Debug: printando valores novos na tela
	print_r( $row);
	
	# =====================================================
	#  Preenchendo o novo arquivo com os valores
	#  novos, linha a linha.
	fputcsv($handleOut, $row, ',');

	$nota[] = array_combine($header, $row);
}
#print_r($nota);

fclose($handleOut);
fclose($handleIn);

