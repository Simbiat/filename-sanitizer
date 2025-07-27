> [!WARNING]  
> This library has been replaced by [Translit](https://github.com/Simbiat/translit) and is no longer maintained, but latest release should still work.

# Filename sanitizer

Small class to replace restricted characters or combinations in filenames. Based on rules for Windows https://docs.microsoft.com/en-us/windows/win32/fileio/naming-a-file but will be useful for *NIX systems as well.  

This will also replace some characters that may be harmful depending on where and how the files are used. If you want to disable that behaviour, call the `sanitize` function with second argument as `false`.  

If you want to remove the characters/combinations instead of replacing, send `true` as third argument.
