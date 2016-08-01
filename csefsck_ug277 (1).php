<?php

/*
															Introduction to Operating Systems
														    File System Checker 
															Submitted By:
															Utkarsh Gautam
															N11489259
															ug277
*/


																//CONSTANT DECLARATIONS//
const devId = 20;
const FREESTART = 1;
const FREEEND = 25;
const ROOT = FREEEND + 1;
const MAX = 10000;
const DIRUID = 1000;
const DIRGID = 1000;
const DIRMODE = 16877;
const INODEMODE = 33261;
const MAXSIZE = 1638400;
const BLOCKSIZE = 4096;
const UIDFILE = 1;
const GIDFILE = 1;
const FREEBLOCKS = 400;


																// GENERATING FILE PATH//

																
$directory = getcwd(); //get present directory
$file0 = 'fusedata.0'; 
$superblock = $directory.'/FS/FS/'.$file0;
$superfile = file_get_contents($superblock,FILE_USE_INCLUDE_PATH); // contents of superblock stored in superfile




															    // CHECKING DEVICE ID   //

											  
											  


function checkdevid($superblock,$superfile)
{
	$parameter = $superfile;
	$parameter = json_decode($parameter);// decoding json format  
	if($parameter->devId==devId)         
		echo "DevID is correct"."<br>";
	else 
	{
		$parameter->devId = devId;
		$parameter = json_encode($parameter);
		file_put_contents($superblock, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
		echo "DevID is incorrect. Appropriate changes have been made."."<br>";
	}

}


                                              // CHECKING TIME AND DATA CONSISTENCY FOR SUPERBLOCK//

											  

function checksuperblock($time,$superfile,$superblock)
{
	$parameter = $superfile;
	$parameter = json_decode($parameter);
	$parameter = checksuperblocktime($time,$parameter,$superblock); // function to check time consistency
	$parameter = checksuperblockdata($time,$parameter,$superblock); // function to check data consistency
	}



function checksuperblocktime($time,$parameter,$superblock)
{
$creationtime=$parameter->creationTime;
if($creationtime > $time)
{
	echo "Time can not be in future and hence it is being replaced by current time"."<br>";
	//$creationtime[1] = $t;
	//$parameter[0] = implode(":",$creationtime);
	$parameter->creationTime=$time;
	$parameter=json_encode($parameter);
	file_put_contents($superblock, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
}

else
	echo "creationTime is correct"."<br>";

return $parameter;
}


function checksuperblockdata($time,$parameter,$superblock)
{
  
  $freestart=$parameter->freeStart;
  $freeend=$parameter->freeEnd;
  $root=$parameter->root;
  $maxblocks=$parameter->maxBlocks;
 
 if ($freestart != FREESTART)
       { echo "Free Start incorrect. Corrected now "."<br>";
		$parameter->freeStart=FREESTART;
		$parameter=json_encode($parameter);	
		file_put_contents($superblock, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
		}
  else
	  echo "Free Start is correct" ."<br>";
			
	if ($freeend != FREEEND)
       { echo "Free End Incorrect. Corrected now."."<br>";
		$parameter->freeEnd = FREEEND;
		$parameter=json_encode($parameter);	
		file_put_contents($superblock, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
		}
	else
		echo "Free End is correct" ."<br>";

	if ($root != ROOT)
       { echo "5)Root number incorrect. Corrected Now."."<br>";
		$parameter->root=ROOT;
		$parameter=json_encode($parameter);	
		file_put_contents($superblock, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
		}
	else
		echo "Root is correct" ."<br>";
    if ($maxblocks != MAX)
       { 
		echo "Max Blocks number incorrect. Corrected Now."."<br>";
		$parameter->maxBlocks = MAX;
		$parameter=json_encode($parameter);	
		file_put_contents($superblock, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
		
		}
	else
		echo "MaxBlocks number is correct" ."<br>";
return $parameter;
}
												
												       //CHECKING ATIME CTIME AND MTIME//

												
												
												
function checktime($time)
{

$directory = getcwd();
$file0 = 'fusedata.';
$superblock = $directory.'/FS/FS/'.$file0;
for ($i=1; $i<=30; $i++)
{



	$file=$superblock.$i;
	$parameter = file_get_contents($file,FILE_USE_INCLUDE_PATH);
	$parameter = json_decode($parameter);
	if(isset($parameter->atime))
	{
	$ATime=$parameter->atime;
	$CTime=$parameter->ctime;
	$MTime=$parameter->mtime;
	
	
	if ( $ATime > $time)  // time should not be in future
		
	{	
		echo "Access Time can not be in future and hence it is being replaced by current time". "Changes in file" .$i."<br>";
		$parameter->atime=$time;
		$parameter=json_encode($parameter);
		file_put_contents($file, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
	}
	
	else
		echo "Access Time is Valid". "In file" .$i."<br>";
	
	
	
	if ( $CTime> $time )
		
	{	
		echo "Creation Time can not be in future and hence it is being replaced by current time". "Changes in file" .$i."<br>";
		$parameter->ctime=$time;
		$parameter=json_encode($parameter);
		file_put_contents($file, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
	}
	
	
	else
		echo "Creation Time is Valid". "In file" .$i."<br>";
	
	if ($MTime > $time )
		
	{	
		echo "Modification Time can not be in future and hence it is being replaced by current time"."Changes in file" .$i."<br>";
		$parameter->mtime=$time;
		$parameter=json_encode($parameter);
		file_put_contents($file, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
	}
	
	else
		echo "Modification Time is Valid". "In file" .$i."<br>";
	
	}
	
}

}
															//CHECKING INDIRECT//
function checkindirect()
{
	$directory = getcwd();
	$file0 = 'fusedata.';
	$superblock = $directory.'/FS/FS/'.$file0;
	
	for ($i=1; $i<=30; $i++)
	
	{
	
	$file=$superblock.$i;
	$parameter = file_get_contents($file,FILE_USE_INCLUDE_PATH);
	$parameter = json_decode($parameter);
	
	if(isset($parameter->indirect)) //Checks only files that have "indirect" as a parameter.
		
		{
			if ($parameter->indirect==1)
			{
	
				
				$location=$parameter->location; //takes location parameter if indirect=1
				$file1=$superblock.$location;   // Creates File Path based on location paramter
				$parameter1 = file_get_contents($file1,FILE_USE_INCLUDE_PATH);
				$file2=$superblock.$location; // Checks location parameter of above created file path
				$exists=file_exists($file2);  // Checks if the file pointed by the location parameter of above file actually exists.
				if ($exists==TRUE)
				{
					echo "Indirect is Valid in File".$i."<br>";
				}
				else 
					{echo "Indirect is invalid in File".$i."Appropriate changes made"."<br>";
					$parameter->indirect=0;
					$parameter=json_encode($parameter);
					file_put_contents($file, $parameter,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
					}
			}
			
		}
	
	}
}




																	//CHECKING DIRECTORY FOR . AND .. //

function checkdirectory()
{
	$directory = getcwd();
	$file0 = 'fusedata.';
	$superblock = $directory.'/FS/FS/'.$file0;
	
	for ($i=1; $i<=30; $i++)
	
	{
	
	$file=$superblock.$i;
	$parameter = file_get_contents($file,FILE_USE_INCLUDE_PATH);
	$parameter = json_decode($parameter);
	
	if(isset($parameter->filename_to_inode_dict)) // Checks files only if there exists a filename_to_inode_dict parameter.
	{
		$parameter1=$parameter->filename_to_inode_dict;
		$N=count($parameter1);
		for ($j=0;$j<=$N;$j++)
		{
			if(isset($parameter1[$j]->type))  // Checks whether the file in consideration is a directory or not.
			{
			$temp=$parameter1[$j]->type;
			if ($temp=='d')
			{
				$parameter2=$parameter1[$j]->name;
				if ($parameter2=='.')
				{
					if ($parameter1[$j]->location==$i) // if name is . the location should be the current file.
					{echo "Valid Name and Location for Directory at location  "; print_r($parameter1[$j]->location); echo"<br>";}
					else
					{echo "Invalid Directory at Location"; print_r($parameter1[$j]->location); echo"<br>";}
					
				}
				if ($parameter2=='..')
				{
					if ($parameter1[$j]->location != $i) // if name is .. the location should be different from the current file.
					{echo "Valid Name and Location for directory at Location " ; print_r($parameter1[$j]->location); echo"<br>";}
					else
					{echo "Invalid Directory at Location"; print_r($parameter1[$j]->location); echo"<br>";}
				}
				else
				{echo "Invalid Name of Directory at Location";print_r($parameter1[$j]->location); echo"<br>";}				
			}
			
			}
		}
	
	}
	}
}
                                                                //CHECKING FREE BLOCK LIST//

function validatefreeblock()
{
	$array = new SplFixedArray(MAX); //initialize an array of size MAX with each element equal to zero initially.
	for ($i=0;$i<MAX;$i++)
	{
		$array[$i]=0;
	}
	
	$directory = getcwd();
	$file0 = 'fusedata.';
	$superblock = $directory.'/FS/FS/'.$file0;
	
	for ($i=1; $i<=30; $i++)           // For Blocks which contain fusedata.x change corresponding value of array element to 2.
	
	{
	
	$file=$superblock.$i;
	$parameter = file_get_contents($file,FILE_USE_INCLUDE_PATH);
	$parameter = json_decode($parameter);
	
	if(isset($parameter->filename_to_inode_dict)) 
	{
		$parameter1=$parameter->filename_to_inode_dict;
		$N=count($parameter1);
		for ($j=0;$j<=$N;$j++)
		{
			if(isset($parameter1[$j]->location))
			{
				$array[$parameter1[$j]->location]=2;
			}
		}
	}
	}
	
	
	
	
	for ($i=1; $i<=25; $i++)  // For Free Block elements given in fusedata.x change corresponding array elements to 1.
	
	{
	
	$file=$superblock.$i;
	$parameter = file_get_contents($file,FILE_USE_INCLUDE_PATH);
	$parameter = json_decode($parameter);
	$N=count($parameter);
	
	for ($j=0;$j<$N;$j++)
	{
		$array[$parameter[$j]]=1;
	}
	}
	
	for ($i=0;$i<=30;$i++)
	{
		$array[$i]=2;
	}
	
	
	for ($i=0;$i<MAX;$i++)  // Now only the free block elements that are not listed will have their corresponding array elements equal to zero.
							// Find those elements and list them. And add them to the Free Block List.
	{
		if ($array[$i]==2)
			
		{
			echo "Block".$i."not included in Free Block List because it stores the file fusedata.".$i."<br>";
		}
		
		if ($array[$i]==0)
		{
			echo "Block".$i."was not included in Free Block List. It is included now"."<br>";
			file_put_contents($superblock.'1', $i,FILE_USE_INCLUDE_PATH| LOCK_EX) or die("File suddenly lost!");
		}
		
		
	}
	
	echo "All Remaining Blocks are included in Free Block List";
	
}



                                                                  //VALIDATING SIZE//



function validatesize()
{
	$directory = getcwd();
	$file0 = 'fusedata.';
	$superblock = $directory.'/FS/FS/'.$file0;
	
	for ($i=1; $i<=30; $i++)  // Validates size according to given conditions.
	
	{
	
	$file=$superblock.$i;
	$parameter = file_get_contents($file,FILE_USE_INCLUDE_PATH);
	$parameter = json_decode($parameter);
	
	if(isset($parameter->size)and isset($parameter->indirect))
	{
		
		
		
		if ($parameter->size < BLOCKSIZE)  
		{
			
			$indirect=$parameter->indirect;
			$size=$parameter->size;
			if ($indirect==0 and $size > 0)
				echo "Size is Valid in File fusedata.".$i."<br>";
			
			if(isset($parameter->filename_to_inode_dict))
			{
				$parameter1=$parameter->filename_to_inode_dict;
				
				$location=$parameter1->location;
				$N=count($location); // Gives length of location array
				$T=$N * BLOCKSIZE;   // Multiply location array with BLOCKSIZE
				$S=($N-1) * BLOCKSIZE; // Multiply ((length of location array -1)) with BLOCKSIZE
				if ( $parameter->size < $t and $parameter->size > $S)
					
				echo "Size is Valid in File fusedata.".$i."<br>";  
			}
		}
		echo "Size is Invalid in File fusedata.".$i."<br>";
	}	
		
		
		
	}
	
	
	
	
}


																	//FUNCTION CALLS//

checkdevid($superblock,$superfile);
$time = time();
checksuperblock($time,$superfile,$superblock);
checktime($time);
checkindirect();
checkdirectory();
validatefreeblock();
validatesize();

