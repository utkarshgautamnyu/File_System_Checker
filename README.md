This is the solution for the assignment under the course Introduction to Operating system (Spring 2016), taught by Professor Daniel Katz.

We were to design a file system checker, which would ratify the following and would correct errors wherever requried:

1)	The DeviceID is correct (20)
2)	All times are in the past, nothing in the future
3)	Validate that the free block list is accurate this includes
a.	Making sure the free block list contains ALL of the free blocks
b.	Make sure than there are no files/directories stored on items listed in the free block list
4)	Each directory contains . and .. and their block numbers are correct
5)	If indirect is 1, that the data in the block pointed to by location pointer is an array
6)	That the size is valid for the number of block pointers in the location array. The three possibilities are:
a.	size<blocksize  should have indirect=0 and size>0
b.	if indirect!=0, size should be less than (blocksize*length of location array)
c.	if indirect!=0, size should be greater than (blocksize*length of location array-1)


