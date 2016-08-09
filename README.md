This is the solution for the assignment under the course <strong> Introduction to Operating system </strong> (Spring 2016), taught by <em>Professor Daniel Katz.<em>

We were to design a file system checker, which would ratify the following and would correct errors wherever requried:

<ol>
<li>	The DeviceID is correct (20)</li>
<li>  All times are in the past, nothing in the future </li>
<li>  Validate that the free block list is accurate this includes 
<ol type="a">
<li> Making sure the free block list contains ALL of the free blocks </li>
<li> Make sure than there are no files/directories stored on items listed in the free block list</li>
</ol>
</li>
<li>  Each directory contains . and .. and their block numbers are correct</li>
<li>	If indirect is 1, that the data in the block pointed to by location pointer is an array</li>
<li>  That the size is valid for the number of block pointers in the location array. The three possibilities are:
<ol type="a">
<li> size<blocksize should have indirect=0 and size>0 </li>
<li> if indirect!=0, size should be less than (blocksize*length of location array)</li>
<li> if indirect!=0, size should be greater than (blocksize*length of location array-1) </li>
</ol>
</li>
</ol>

