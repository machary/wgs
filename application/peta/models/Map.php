<?php
/**
 * Model Map
 *
 * Menyuplai data ke controller peta
 *
 * @author Kanwil
 */

class Peta_Model_Map
{
	/**
	 * Mengembalikan idpangkalan milik lanal di suatu titik
	 * @param float $longitude
	 * @param float $latitude
	 * @return null|int idpangkalan lanal
	 */
	public function getIdLanalFromCoord($longitude, $latitude) 
	{
		// ambil dari database
		$table = new Zend_Db_Table('public.lanal_area');
		$query = $table->select()
			->from('public.lanal_area', array('wilayah', 'idpangkalan'))
			->where("geomfromtext('POINT({$longitude} {$latitude})', 4326) && geom")
		;
		$row = $table->fetchRow($query);
		// tampilkan
		if ($row && $row->idpangkalan) {
			return $row->idpangkalan;
		} else {
			return null;
		}
	}

    public function getIdLanalFromCoord2($longitude, $latitude)
    {
        // ambil dari database
        $table = new Zend_Db_Table('public.lanal');
        $query = $table->select()
            ->from('public.lanal', array('lantamal','lanal', 'id_master'))
            ->where(new Zend_Db_Expr("ST_DWithin(geom,ST_GeomFromText('POINT({$longitude} {$latitude})', 4326), 0.2)"))
        ;

        $row = $table->fetchRow($query);
        // tampilkan
        if ($row && $row->id_master) {
            return $row->id_master;
        } else {
            return null;
        }
    }

	//get depo pertamina berdasarkan koordinat
	//@author irfan.muslim@gmail.com

	public function getIdDepoFromCoord($longitude, $latitude)
	{
		// ambil dari database
		$table = new Zend_Db_Table('public.pertamina');
		$query = $table->select()
			->from('public.pertamina')
			->where(new Zend_Db_Expr("ST_DWithin(geom,ST_GeomFromText('POINT({$longitude} {$latitude})', 4326), 0.2)"))
		;

		$row = $table->fetchRow($query);
		// tampilkan
		if ($row && $row->gid) {
			return $row->gid;
		} else {
			return null;
		}
	}

	/**
	 * Mengembalikan informasi lengkap suatu pangkalan
	 * @param int $idpangkalan
	 * @return null|array nama tabel fasilitas menjadi key
	 */
	public function getPangkalanDetail($idpangkalan)
	{
		// pastikan $idpangkalan ada
		$dbtable = new Zend_Db_Table('master.pangkalan');
		$query = $dbtable->select()
			->where('idpangkalan = ?', $idpangkalan)
		;
		$pangkalan = $dbtable->fetchRow($query);
		if (!$pangkalan) {
			return null; // nothing to do here
		}
		$result = $pangkalan->toArray();
		
		$tables = array(
			'fasbek', 'fasilitas_bengkel',
			'fasilitas_dermaga', 'fasilitas_dock',
			'fasilitas_jaringan_listrik', 'fasilitas_labuh_udara',
			'fasilitas_mess', 'fasilitas_ranmor',
			'fasilitas_rumah', 'fasilitas_rumah_sakit',
			'fasilitas_tanah', 'fasilitas_umum',
		);
		foreach ($tables as $tableName) {
			$dbtable = new Zend_Db_Table('master.' . $tableName);
			$query = $dbtable->select()
				->where('idpangkalan = ?', $idpangkalan)
			;
			$result[$tableName] = $dbtable->fetchAll($query)->toArray();
		}
		return $result;
	}
	
	/**
	 * Convert string seperti "sonar_name" menjadi "Sonar Name"
	 */
	public function fieldToName($field)
	{
		// di lowercase, ganti '_' jadi ' ', uppercase huruf awal tiap kata
		return ucwords(str_replace('_', ' ', strtolower($field)));
	}
}