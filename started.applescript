tell application "OmniFocus"
	set _items to {}
	set _item to {}
	set _task to {}
	set _tasks to {}
	tell the default document to tell the front document window
		set perspective name to "Started"
		set AllTrees to trees of content
		repeat with i from 2 to count of AllTrees
			set dTrees to descendant trees of item i of AllTrees
			set end of _item to name of item i of AllTrees
			set end of _items to _item
			repeat with t from 1 to count of dTrees
				set end of _task to name of (item t of dTrees)
			end repeat
			set end of _item to _task
			set _task to {}
			set _item to {}
		end repeat
	end tell
end tell

tell application "JSON Helper"
	return make JSON from _items
end tell