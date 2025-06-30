<?php
// escreve o arquivo contendo o gráfico de barras
function mismatch_response_service_desenhar_grafico_barras($largura, $altura, $data, $valor_maximo, $nome_arquivo)
{
    $img = imagecreatetruecolor($largura, $altura); //cria imagem vazia

    //define um fundo branco com texto preto e gráficos cinza

    $cor_branca = imagecolorallocate($img, 255, 255, 255); //branco
    $cor_preta = imagecolorallocate($img, 0, 0, 0); //preto
    $cor_cinza = imagecolorallocate($img, 192, 192, 192); //cinza

    imagefilledrectangle($img, 0, 0, $largura, $altura, $cor_branca); // preenche o fundo

    $largura_barra = $largura / ((count($data) * 2) + 1);

    for ($i = 0; $i < count($data); $i++) {

        imagefilledrectangle(
            $img,
            ($i * $largura_barra * 2) + $largura_barra,
            $altura,
            ($i * $largura_barra * 2) + ($largura_barra * 2),
            $altura - (($altura / $valor_maximo) * $data[$i][1]),
            $cor_preta
        );

        imagestringup(
            $img,
            5,
            ($i * $largura_barra * 2) + ($largura_barra),
            $altura - 5,
            $data[$i][0],
            $cor_branca
        );
    }

    imagerectangle($img, 0, 0, $largura - 1, $altura - 1, $cor_cinza);

    for ($i = 1; $i <= $valor_maximo; $i++) {
        imagestring($img, 5, 0, $altura - ($i * ($altura / $valor_maximo)), $i, $cor_preta);
    }

    imagepng($img, $nome_arquivo, 5);

    imagedestroy($img);
}
