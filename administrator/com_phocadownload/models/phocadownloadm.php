<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class PhocaDownloadCpModelPhocaDownloadM extends JModelAdmin
{
	
	protected $option 		= 'com_phocadownload';
	protected $text_prefix 	= 'com_phocadownload';
	
	protected $fileCount		= 0;
	protected $categoryCount	= 0;
	
	function __construct() {
		$this->fileCount 		= 0;
		$this->categoryCount 	= 0;
		parent::__construct();
	}
		
		function setFileCount($count) {
		$this->fileCount = $this->fileCount + $count;
	}
	
	function setCategoryCount($count) {
		$this->categoryCount = $this->categoryCount + $count;
	}
	
	function save($data) {	
	
		$app	= JFactory::getApplication();
	
		$post	= JRequest::get('post');
		$data	= JRequest::getVar('jform', array(0), 'post', 'array');
		
		
		if(isset($post['foldercid'])) {
			$data['foldercid']	= $post['foldercid'];
		}
		if(isset($post['cid'])) {
			$data['cid']		= $post['cid'];
		}
		
		if (isset($data['catid']) && (int)$data['catid'] > 0) {
			$data['catid']		= (int)$data['catid'];
		} else {
			$data['catid']		= 0;
		}
		
		//Get folder variables from Helper
		$path 			= PhocaDownloadHelper::getPathSet();
		$origPath 		= $path['orig_abs_ds'];
		$origPathServer = str_replace(DS, '/', JPath::clean($path['orig_abs_ds']));
		
		
		
		// Cache all existing categories	
		$query = 'SELECT id, title, parent_id'
	    . ' FROM #__phocadownload_categories' ;
		$this->_db->setQuery( $query );
	    $existingCategories = $this->_db->loadObjectList() ;
		
		// Cache all existing files
		$query = 'SELECT catid, filename'
	    . ' FROM #__phocadownload';	    
		$this->_db->setQuery( $query );
	    $existingFiles = $this->_db->loadObjectList() ;
		
		$result->category_count = 0;
		$result->image_count 	= 0;
		
	
		
		// Category will be saved - Files will be saved in recursive function
		if (isset($data['foldercid'])) {
			foreach ($data['foldercid'] as $foldername) {
				if (strlen($foldername) > 0) {
					$fullPath 		= $path['orig_abs_ds'].$foldername;
				
					$result 		= $this->_createCategoriesRecursive( $origPathServer, $fullPath, $existingCategories, $existingFiles, $data['catid'], $data );					
				}		
			}
		}
		
		// Only Files will be saved
		
		if (isset($data['cid'])) {
			foreach ($data['cid'] as $filename) {				
				if ($filename) {
					//$ext = strtolower(JFile::getExt($filename));
						
					$row =& $this->getTable('phocadownload');
					
					$datam = array();
					$datam['published']		= $data['published'];
					$datam['catid']			= $data['catid'];
					$datam['approved']		= $data['approved'];
					$datam['language']		= $data['language'];
					$datam['filename']		= $filename;
					
					if ($data['title']	!= '') {
						$datam['title']		= $data['title'];
					} else {
						$datam['title']		= PhocaDownloadHelper::getTitleFromFilenameWithoutExt($filename);
					}
					
					if ($data['alias']	!= '') {
						$datam['alias']		= $data['alias'];
					} else {
						$datam['alias']		= $data['alias']; // PhocaDownloadHelper::getAliasName($datam['title']);
					}
				
					// Save
					// Bind the form fields to the Phoca download table
					if (!$row->bind($datam)) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}

					// Create the timestamp for the date
					$row->date = gmdate('Y-m-d H:i:s');

					// if new item, order last in appropriate group
				
					if (!$row->id) {
						$where = 'catid = ' . (int) $row->catid ;
						$row->ordering = $row->getNextOrder( $where );
					}
					

					// Make sure the Phoca download table is valid
					if (!$row->check()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}

					// Store the Phoca download table to the database
					if (!$row->store()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					$result->image_count++;
					
				}
			}
			$this->setfileCount($result->image_count);

		}
		
		$msg = $this->categoryCount. ' ' .JText::_('COM_PHOCADOWNLOAD_CATEGORIES_ADDED') .', '.$this->fileCount. ' ' . JText::_('COM_PHOCADOWNLOAD_FILES_ADDED');
		$app->redirect(JRoute::_('index.php?option=com_phocadownload&view=phocadownloadfiles', false), $msg);
		
		return true;
		
	}
	
	protected function _createCategoriesRecursive(&$origPathServer, $path, &$existingCategories, &$existingFiles, $parentId = 0, $data = array() ) {
		$totalresult->files_count 		= 0 ;
		$totalresult->category_count	= 0 ;
				
		$categoryName 	= basename($path);
		$id 			= $this->_getCategoryId( $existingCategories, $categoryName, $parentId ) ;
		$category 		= null;

		// Full path: eg. "/home/www/joomla/files/categ/subcat/"
		$fullPath	   	= str_replace(DS, '/', JPath::clean(DS . $path));
		// Relative path eg "categ/subcat"
		$relativePath 	= str_replace($origPathServer, '', $fullPath);	
		
		// Category doesn't exist
		if ( $id == -1 ) {
		  $row =& $this->getTable('phocadownloadcat');
		  
		  $row->published 	= $data['published'];
		 // $row->approved	= $data['approved'];
		  $row->language	= $data['language'];
		  $row->parent_id 	= $parentId;
		  $row->title 		= $categoryName;
		  
		  // Create the timestamp for the date
		  $row->date 		= gmdate('Y-m-d H:i:s');
		 // $row->alias 		= $row->title; //PhocaDownloadHelper::getAliasName($categoryName);
		  //$row->userfolder	= ltrim(str_replace(DS, '/', JPath::clean($relativePath )), '/');
		  $row->ordering 	= $row->getNextOrder( "parent_id = " . $this->_db->Quote($row->parent_id) );				
		
		  if (!$row->check()) {
			JError::raiseError(500, $row->getError('Check Problem') );
		  }

		  if (!$row->store()) {
			JError::raiseError(500, $row->getError('Store Problem') );
		  }
		  
		  $category 			= new JObject();
		  $category->title 		= $categoryName ;
		  $category->parent_id 	= $parentId;
		  $category->id 		= $row->id;
		  $totalresult->category_count++;
		  $id = $category->id;
		  $existingCategories[] = &$category ;
		  $this->setCategoryCount(1);//This subcategory was added
		}
		
		

		// Add all files from this folder
		$totalresult->image_count += $this->_addAllFilesFromFolder( $existingFiles, $id, $path, $relativePath, $data );
		$this->setfileCount($totalresult->image_count);
		
		// Do sub folders
		$parentId 		= $id;		
		$folderList 	= JFolder::folders( $path, $filter = '.', $recurse = false, $fullpath = true, $exclude = array() );		
		// Iterate over the folders if they exist
		if ($folderList !== false) {
			foreach ($folderList as $folder) {
				//$this->setCategoryCount(1);//This subcategory was added
				$folderName = $relativePath .'/' . str_replace($origPathServer, '', $folder);
				$result = $this->_createCategoriesRecursive( $origPathServer, $folder, $existingCategories, $existingFiles, $id , $data);
				$totalresult->image_count += $result->image_count ;
				$totalresult->category_count += $result->category_count ;
			}
		}
		return $totalresult ;
	}
	
	protected function _getCategoryId( &$existingCategories, &$title, $parentId ) {
	    $id = -1;
		$i 	= 0;
		$count = count($existingCategories);
		while ( $id == -1 && $i < $count ) {
		
			if ( $existingCategories[$i]->title == $title &&
			     $existingCategories[$i]->parent_id == $parentId ) {
				$id = $existingCategories[$i]->id ;
			}
			$i++;
		}
		return $id;
	}
	
	protected function _FileExist( &$existing_image, &$filename, $catid ) {
	    $result = false ;
		$i 		= 0;
		$count = count($existing_image);
		
		while ( $result == false && $i < $count ) {
			if ( $existing_image[$i]->filename == $filename &&
			     $existing_image[$i]->catid == $catid ) {
				$result = true;
			}
			$i++;
		}
		return $result;
	}
	
	protected function _addAllFilesFromFolder(&$existingImages, $category_id, $fullPath, $rel_path, $data = array()) {
		$count = 0;
		$fileList = JFolder::files( $fullPath );
		natcasesort($fileList);
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img

		if ($fileList !== false) {
			foreach ($fileList as $filename) {
			    $storedfilename	= ltrim(str_replace(DS, '/', JPath::clean($rel_path . DS . $filename )), '/');
				//$ext = strtolower(JFile::getExt($filename));
								
				if (JFile::exists($fullPath.DS.$filename) && 
					substr($filename, 0, 1) != '.' && 
					strtolower($filename) !== 'index.html' &&
					!$this->_FileExist($existingImages, $storedfilename, $category_id) ) {
					
					$row =& $this->getTable('phocadownload');
					
					$datam = array();
					$datam['published']		= $data['published'];
					$datam['catid']			= $category_id;
					$datam['filename']		= $storedfilename;
					$datam['approved']		= $data['approved'];
					$datam['language']		= $data['language'];
					if ($data['title']	!= '') {
						$datam['title']		= $data['title'];
					} else {
						$datam['title']		= PhocaDownloadHelper::getTitleFromFilenameWithoutExt($filename);
					}
					
					if ($data['alias']	!= '') {
						$datam['alias']		= $data['alias'];
					} else {
						$datam['alias']		= $data['alias'];//PhocaDownloadHelper::get AliasName($datam['title']);
					}

					// Save
					// Bind the form fields to the Phoca download table
					if (!$row->bind($datam)) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}

					// Create the timestamp for the date
					$row->date = gmdate('Y-m-d H:i:s');

					// if new item, order last in appropriate group
					if (!$row->id) {
						$where = 'catid = ' . (int) $row->catid ;
						$row->ordering = $row->getNextOrder( $where );
					}

					// Make sure the Phoca download table is valid
					if (!$row->check()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}

					// Store the Phoca download table to the database
					if (!$row->store()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					
					/*if ($this->firstImageFolder == '') {
						$this->setFirstImageFolder($row->filename);
					}*/
					
					$image 				= new JObject();
					$image->filename 	= $storedfilename ;
					$image->catid 		= $category_id;
					$existingImages[] 	= &$image ;
					$count++ ;
				}
				 
			}
		}
		
	//	$this->setfileCount($count);
		return $count;
	}
	
	
	
	
	
	
	
	
	
	
	public function getForm($data = array(), $loadData = true) {
		
		$form 	= $this->loadForm('com_phocadownload.phocadownloadmanager', 'phocadownloadmanager', array('control' => 'jform', 'load_data' => $loadData));		
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	public function getTable($type = 'PhocaDownload', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownloadm.edit.phocadownloadm.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	/*
	function getFolderState($property = null) {
		static $set;

		if (!$set) {
			$folder		= JRequest::getVar( 'folder', '', '', 'path' );
			$upload		= JRequest::getVar( 'upload', '', '', 'int' );
			$manager	= JRequest::getVar( 'manager', '', '', 'path' );
			
			$this->setState('folder', $folder);
			$this->setState('manager', $manager);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			
			$set = true;
		}
		return parent::getState($property);
	}

	function getFiles() {
		$list = $this->getList();
		return $list['files'];
	}

	function getFolders() {
		$list = $this->getList();
		return $list['folders'];
	}

	function getList() {
		static $list;

		//Params
		$params	= &JComponentHelper::getParams( 'com_phocadownload' );

		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}
		
		// File Manager, Icon Manager
		$manager = $this->getState('manager');
		if ($manager == 'undefined') {
			$manager = '';
		}
		$path = phocadownloadHelper::getPathSet($manager);

		//$path = phocadownloadHelper::getPathSet();
		
		// Initialize variables
		if (strlen($current) > 0) {
			$orig_path = $path['orig_abs_ds'].$current;
		} else {
			$orig_path = $path['orig_abs_ds'];
		}
		$orig_path_server 	= str_replace(DS, '/', $path['orig_abs'] .'/');
		
		
		// Absolute Path defined by user
		$absolutePath	= $params->get('absolute_path', '');
		if ($absolutePath != '') {
			$orig_path_server 		= str_replace(DS, '/', JPath::clean($absolutePath .'//') );//$absolutePath ;
		}
		
		$files 		= array ();
		$folders 	= array ();

		// Get the list of files and folders from the given folder
		$file_list 		= JFolder::files($orig_path);
		$folder_list 	= JFolder::folders($orig_path, '', false, false, array());
		
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img
		if ($file_list !== false) {
			foreach ($file_list as $file) {
				if (is_file($orig_path.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {			
						$tmp 							= new JObject();
						$tmp->name 						= basename($file);
						$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($orig_path . DS . $file));
						$tmp->path_without_name_relative= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $tmp->path_with_name);
						
						$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($orig_path . DS . $file));
						$tmp->path_with_name_relative_no= str_replace($orig_path_server, '', $tmp->path_with_name);
						
						$files[] = $tmp;
						
				}	
			}
		}

		// Iterate over the folders if they exist
		if ($folder_list !== false) {
			foreach ($folder_list as $folder)
			{
				$tmp 							= new JObject();
				$tmp->name 						= basename($folder);
				$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($orig_path . DS . $folder));
				$tmp->path_without_name_relative= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $tmp->path_with_name);
				$tmp->path_with_name_relative_no= str_replace($orig_path_server, '', $tmp->path_with_name);	

				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'files' => $files);
		return $list;
	}*/
}
?>