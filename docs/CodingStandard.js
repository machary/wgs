"use strict"; // <-- kalau berani
/**
 * Contoh source code referensi coding standard JavaScript
 *
 * Sebagian besar aturan mengikuti coding standard PHP untuk memudahkan
 * Penamaan menggunakan camelCase
 * Gunakan TAB untuk indentasi BUKAN SPASI
 * Kebanyakan dikutip dari crockford:
 * http://javascript.crockford.com/code.html
 *
 * Penamaan File:
 * - untuk file yang berisi deklarasi class, pakai nama class tersebut
 *   ga perlu class A_B_C jadi /A/B/C.js karena class JS biasanya sederhana
 *   misal: file AtomicBomb.js berisi class AtomicBomb
 * - untuk file yang berisi kumpulan fungsi, beri nama terserah,
 *   all lowercase dan pemisah kata pakai -
 *   misal: file voting-sesuatu.js berisi fungsi-fungsi berhubungan dengan voting
 *
 * <Deskripsi singkat isi file>
 * @author Kontributor utama file
 */

/**
 * Contoh deklarasi class
 */
function ContohClassA(a) {
	// constructor
	this.varA = a;
}

ContohClassA.prototype = {
	// Public members
	varA: 'initial value',
	
	/**
	 * Contoh method public
	 * @return string 
	 */
	methodA: function() { // <-- kurawal buka di baris yg sama dengan function
		// string pakai kutip tunggal aja, samain dengan PHP
		var asdf = 'jklmn' + '1234';
		return 'hello world';
	}
	
	/**
	 * Contoh array dan object
	 */
	contohSyntax: function() {
		var arr1 = [1, 2, 3, 'asdf', 'lala'];
		var arr2 = [
			'multi', 'line',
			'array' // <-- JANGAN ada trailing comma
		];
		var obj1 = {
			kunci: 'nilai',
			keyKey: 1900',
			spasi: function() {
				return ' ';
			} // <-- JANGAN ada trailing comma
		}
	}
	
	/**
	 * Contoh conditional dan loop
	 */
	contohCompound: function() {
		if (condition) {
			// statements
		} else if (condition) {
			// statements
		} else {
			// statements
		}
		
		var msg;
		// berbeda dari crockford, case di dalam switch diberi indentasi
		switch (kunjungan) {
			case 1:
				msg = 'Anda jarang datang';
				break;
			
			// jika memang sengaja tidak diberi break, beri komen, contoh:
			case 2: // break sengaja dihilangkan
			case 3:
				lessThanFour = true;
				// break sengaja dihilangkan
			case 4:
				msg = 'Anda mulai sering datang';
				break;
				
			default:
				msg = 'Anda pelanggan setia';
				break; // kondisi default pun diberi break;
		}
		
		for (var i = 0; i < arr.length; i++) {
			doSomething(arr[i]);
		}
		
		for (var k in obj) {
			doSomething(obj[k]);
		}
	}
}

/**
 * Contoh inheritance
 * @author Kontributor lain
 */
function ContohClassB(a, b) {
	ContohClassA.call(this, a); // <-- inherit constructor
	this.varB = b;
}

ContohClassB.prototype = new ContohClassA; // <-- inherit methods
// Deskripsi singkat member
ContohClassB.prototype.varB = 'initial value b';
	
/**
 * Method tambahan
 * @param integer xx 
 * @param integer yy
 * @return float
 */
ContohClassB.prototype.methodB = function(xx, yy) {
	return Math.sqrt(xx*xx + yy*yy);
};

var objectB = new ContohClassB(100, 200);

/** 
 * Contoh deklarasi fungsi
 * @param integer|float param1
 * @param integer|float param2
 * @return boolean
 */
function contohFungsi(param1, param2) {
	var hasil = (param1 + param2) / (param1 - param2);
	return (hasil > 100) ? true : false;
}
