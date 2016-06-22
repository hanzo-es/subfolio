<?php defined('SYSPATH') OR die('No direct access allowed.');

class Archive_Zip64_Driver implements Archive_Driver {
    
	protected $archive;

	/**
	 * Creates an archive and optionally, saves it to a file.
	 *
	 * @param   array    filenames to add
	 * @param   string   file to save the archive to
	 * @return  boolean
	 */
	public function create($paths, $filename = FALSE) {
	    
	    $this->archive = new ZipArchive;
	    $tmpnam = tempnam(sys_get_temp_dir(), "FOO");
	    
	    $result = $this->archive->open($tmpnam, ZipArchive::CREATE);
	    if ($result !== TRUE) {
	        error_log("Couldn't create zip64 archive");
	        return false;
	    }
	    
	    // Sort the paths to make sure that directories come before files
		sort($paths);
		
		foreach ($paths as $set)
		{
			// Add each path individually
			$this->add_data($set[0], $set[1], NULL);
		}
		
		$this->archive->close();
		
		if ($filename === FALSE) {
	        // Return the contents of the archive as a string
	        $contents = file_get_contents($tmpnam);
	        unlink($tmpnam);
	        return $contents;
	    }
		
		rename($tmpname, $filename);
		
		return true;
	} 
	
	/**
	 * Add data to the archive.
	 *
	 * @param   string   filename
	 * @param   string   name of file in archive
	 * @return  void
	 */
	public function add_data($file, $name, $contents = NULL) {
	    $isdir = (substr($file, -1) === '/') ? TRUE : FALSE;
	    
	    if ($isdir) {
	        $this->archive->addEmptyDir($name);
	    } else {
	        $this->archive->addFile($file, $name);
	    }
	}
    
} // End Archive_Zip64_Driver Class