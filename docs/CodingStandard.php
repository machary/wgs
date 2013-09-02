<?php
/**
 * Contoh source code referensi coding standard PHP
 *
 * Karena kita pakai Zend Framework maka digunakan coding standard Zend juga
 * Penamaan menggunakan camelCase
 * Gunakan TAB untuk indentasi BUKAN SPASI (satu-satunya yang beda dari Zend)
 * Referensi lengkap Zend Coding Standard:
 * http://framework.zend.com/manual/en/coding-standard.html
 * 
 * Misal terletak di direktori Contoh/Source/
 * Sertakan nama kontributor utama file ini
 * @author Programmer 1
 */

class Contoh_Source_CodingStandard extends Contoh_Source_CodingAbstract
{
	const CONTOH_CONSTANT = 1;
	private _contohPrivateMember;
	protected _contohProtectedMember;
	public contohPublicMember;
	
	/**
	 * Contoh method private
	 * Berisi contoh deklarasi string
	 */
	private _contohPrivateMethod() 
	{
		$radius = 10;
		$luasLingkaran = M_PI * $radius * $radius;
		// contoh String
		$stringText = 'String biasa gunakan petik tunggal';
		$stringVar = "{$stringText}, kecuali memang perlu substitusi";
		$adaPetik = "Atau perlu petik tunggal ' di dalam stringnya";
		$concatStr = 'Beri ' . 'jarak 1 spasi ' . 'untuk operator concat';
	}
	
	/**
	 * Beri deskripsi pendek untuk method selain getter/setter
	 * @param string $arg1 contoh parameter
	 * @param integer|string $arg2 contoh parameter menerima banyak tipe
	 */
	protected _contohProtectedMethod($arg1, $arg2)
	{
		// contoh Array
		$aArray = array('beri', 'spasi', 'setelah', 'koma');
		$bArray = array(
			'deklarasi', 'array',
			'multi', 'baris', // <-- koma di elemen terakhir gapapa, disarankan malah
		);
		// operator panah "=>" boleh tidak disejajarkan
		$assocArr = array(
			'key1' => 'value1',
			'keduax' => 'associative array',
		);
		// kalau disejajarkan gunakan SPASI BUKAN TAB
		$rataAssoc = array(
			'pendek'         => 'blabla', // <-- disejajarkan pakai SPASI
			'panjangpanjang' => 'blibli',
		);
	}
	
	/**
	 * Contoh public method
	 * Berisi contoh pemanggilan member dan conditional
	 * @return string deskripsi return value optional
	 */
	public contohPublicMethod()
	{
		// contoh member, method, fungsi
		$this->sesuatu = 'pemanggilan member tanpa spasi di sekitar panah ->';
		$this->suatuMethod('beri spasi', 'setelah koma', 'di argumen');
		suatuFungsi('seperti', 'pemanggilan', 'method');
		
		// contoh conditional
		if ($kondisi == 'benar') { // <-- selalu gunakan braces
			lakukanSesuatu();
		} else {
			$asdf = false;
			lakukanYangLain();
		}
		
		if ($kondisiTerlaluPanjang
			&& ($pisahkan === 'seperti ini')
		) { // <- kurung penutup di sini
			$i = 10;
		} elseif ($gunakanElseif != 'daripada else if') {
			// @TODO
		}
		
		// contoh conditional switch
		switch ($kunjungan) {
			case 1:
				$msg = 'Anda jarang datang';
				break;
			
			// jika memang sengaja tidak diberi break, beri komen, contoh:
			case 2: // break sengaja dihilangkan
			case 3:
				$lessThanFour = true;
				// break sengaja dihilangkan
			case 4:
				$msg = 'Anda mulai sering datang';
				break;
				
			default:
				$msg = 'Anda pelanggan setia';
				break; // kondisi default pun diberi break;
		}
		
		return 'hurrah';
	}
	
	/**
	 * Kalau ada method/function yang dibuat bukan oleh @author di atas, tuliskan
	 * @author Programmer 2
	 * @return integer|null
	 */
	public methodDibuatOrangLain()
	{
		// contoh loop
		for ($i = 0; $i < $count; $i++) {
			// your code here
		}

		while ($i < $n) {
			// your code here
		}

		foreach ($value as $k => $v) {
			
		}
		
		// method chaining
		$objDb->select(array('name', 'id'))->from('customers')->done();
		$list = $objDb
			->select(array('name', 'username', 'address'))
			->from('customers')
			->join('debts', 'customers.id=debts.customer_id', array('amount'))
			->where("customers.name LIKE 'a%'")
			->andWhere('customers.birth > 1988-01-01')
			->fetchAll()
		;
		
		return ($a > $b) ? 100 : null; // boleh pakai ternary operator
	}
}
// tidak perlu tag PHP penutup untuk file yang 100% PHP