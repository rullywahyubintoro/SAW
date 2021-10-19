<?php
/**
 * Sumber : https://bukuinformatika.com/metode-simple-additive-weighting-saw/
 */

/** Data */
$kriteria = [
	[
		'kode' => 'c01',
		'nama' => 'IPK',
		'atribut' => 'benefit', /** benefit -> Semakin besar nilai semakin bagus */
		'bobot' => 0.25,
	],
	[
		'kode' => 'c02',
		'nama' => 'Penghasilan orang tua',
		'atribut' => 'cost', /** cost -> Semakin kecil nilai semakin bagus */
		'bobot' => 0.15,
	],
	[
		'kode' => 'c03',
		'nama' => 'Jumlah tanggungan orang tua',
		'atribut' => 'benefit',
		'bobot' => 0.2,
	],
	[
		'kode' => 'c04',
		'nama' => 'Prestasi',
		'atribut' => 'benefit',
		'bobot' => 0.3,
	],
	[
		'kode' => 'c05',
		'nama' => 'Lokasi rumah dari kampus',
		'atribut' => 'cost',
		'bobot' => 0.1,
	],
];

$alternatif = [
	[
		'kode' => 'a01',
		'nama' => 'Alternatif 1',
		'c01' => 3.92,
		'c02' => 2500000,
		'c03' => 2,
		'c04' => 4,
		'c05' => 100,
	],
	[
		'kode' => 'a02',
		'nama' => 'Alternatif 2',
		'c01' => 3.95,
		'c02' => 4000000,
		'c03' => 2,
		'c04' => 3,
		'c05' => 89,
	],
	[
		'kode' => 'a03',
		'nama' => 'Alternatif 3',
		'c01' => 3.40,
		'c02' => 6500000,
		'c03' => 3,
		'c04' => 2,
		'c05' => 70,
	],
	[
		'kode' => 'a04',
		'nama' => 'Alternatif 4',
		'c01' => 4.00,
		'c02' => 3500000,
		'c03' => 4,
		'c04' => 4,
		'c05' => 120,
	],
	[
		'kode' => 'a05',
		'nama' => 'Alternatif 5',
		'c01' => 3.20,
		'c02' => 1000000,
		'c03' => 2,
		'c04' => 1,
		'c05' => 140,
	],
];

/** Normalisasi */
$normalisasi = [];
foreach($alternatif as $rAlternatif){
	$arrNormalisasi = [
		'kode' => $rAlternatif['kode'],
		'nama' => $rAlternatif['nama'],
	];
	foreach($kriteria as $rKriteria){
		$arrNormalisasi[$rKriteria['kode']] = $rKriteria['atribut'] == 'benefit' ? $rAlternatif[$rKriteria['kode']] / max(array_column($alternatif,$rKriteria['kode'])) : min(array_column($alternatif,$rKriteria['kode'])) / $rAlternatif[$rKriteria['kode']];
	}
	$normalisasi[] = $arrNormalisasi;
}

/** Normalisasi Terbobot */
$normalisasiTerbobot = [];
foreach($normalisasi as $rNormalisasi){
	$arrNormalisasiTerbobot = [
		'kode' => $rNormalisasi['kode'],
		'nama' => $rNormalisasi['nama'],
	];
	foreach($kriteria as $rKriteria){
		$arrNormalisasiTerbobot[$rKriteria['kode']] = $rNormalisasi[$rKriteria['kode']] * $rKriteria['bobot'];
	}
	$normalisasiTerbobot[] = $arrNormalisasiTerbobot;
}

/** Total */
$total = [];
foreach($normalisasiTerbobot as $rNormalisasiTerbobot){
	$preferensi = 0;
	foreach($kriteria as $rKriteria){
		$preferensi += $rNormalisasiTerbobot[$rKriteria['kode']];
	}
	$total[] = [
		'kode' => $rNormalisasiTerbobot['kode'],
		'nama' => $rNormalisasiTerbobot['nama'],
		'preferensi' => $preferensi,
	];
}

/** SORT */
$kTotal = array_column($total, 'preferensi');
array_multisort($kTotal, SORT_DESC, $total);
echo json_encode($total);
?>