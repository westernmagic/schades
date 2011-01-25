#!/bin/bash
for file in `find * -type f -d 0 \! -name version.sh \! -name agpl.txt && find class -type f && find settings -type f` ; do
	sed -i -e 's/@version.*/@version '"$1"'/' $file
	rm $file*-e
done