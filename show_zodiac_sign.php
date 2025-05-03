<?php
// show_zodiac_sign.php
include('assets/layouts/header.php');

$data_nascimento = $_POST['data_nascimento'] ?? '';
$signos = simplexml_load_file('signos.xml');
$signo_encontrado = null;

if ($data_nascimento) {
    $data = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if ($data) {
        $mes_dia = $data->format('m/d');
        $ano_atual = date('Y');
        
        foreach ($signos->signo as $signo) {
            $inicio = DateTime::createFromFormat('d/m/Y', $signo->datainicio . '/' . $ano_atual);
            $fim = DateTime::createFromFormat('d/m/Y', $signo->dataFim . '/' . $ano_atual);
            
            // Ajuste para signos que cruzam o ano (ex: Capricórnio)
            if ($signo->signoNome == 'Capricórnio') {
                $inicio = DateTime::createFromFormat('d/m/Y', $signo->datainicio . '/' . ($ano_atual - 1));
                if ($data < $fim) {
                    $fim->modify('+1 year');
                }
            }
            
            $data_nasc_no_ano = DateTime::createFromFormat('d/m/Y', $data->format('d/m') . '/' . $ano_atual);
            if ($signo->signoNome == 'Capricórnio' && $data->format('m') == '12') {
                $data_nasc_no_ano->modify('-1 year');
            }
            
            if ($data_nasc_no_ano >= $inicio && $data_nasc_no_ano <= $fim) {
                $signo_encontrado = $signo;
                break;
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if ($signo_encontrado): ?>
            <h2 class="text-center mb-4"><?php echo $signo_encontrado->signoNome; ?></h2>
            <p class="text-center"><?php echo $signo_encontrado->descricao; ?></p>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                Data inválida ou signo não encontrado.
            </div>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>

