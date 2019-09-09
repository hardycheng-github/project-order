// I18N constants -- Chinese GB
// by Dave Lo -- dlo@interactivetools.com
HTMLArea.I18N = {

	// the following should be the filename without .js extension
	// it will be used for automatically load plugin language.
	lang: "gb",

	tooltips: {
		bold:           "粗體",
		italic:         "斜體",
		underline:      "底線",
		strikethrough:  "刪除線",
		subscript:      "下標",
		superscript:    "上標",
		justifyleft:    "位置靠左",
		justifycenter:  "位置居中",
		justifyright:   "位置靠右",
		justifyfull:    "位置左右平等",
		orderedlist:    "順序清單",
		unorderedlist:  "無序清單",
		outdent:        "減小行前空白",
		indent:         "加寬行前空白",
		forecolor:      "文字顏色",
		hilitecolor:     "背景顏色",
		horizontalrule: "水平線",
		createlink:     "插入連結",
		insertimage:    "插入圖形",
		inserttable:    "插入表格",
		htmlmode:       "切換HTML原始碼",
		popupeditor:    "放大",
		about:          "關於 HTMLArea",
		showhelp:       "說明",
		textindicator:  "字體例子",
		undo:           "回原",
		redo:           "重作",
		cut:            "剪下",
		copy:           "複製",
		paste:          "貼上",
		lefttoright:    "從左到又",
		righttoleft:    "從又到左",
		removeformat:   "Remove formatting",
		print:          "列印",
		killword:       "清除標籤"
	},

	buttons: {
		"ok":           "OK",
		"cancel":       "取消"
	},

	msg: {
		"Path":         "途徑",
		"TEXT_MODE":    "你在用純字編輯方式.  用 [<>] 按鈕轉回 所見即所得 編輯方式.",

		"IE-sucks-full-screen" :
		// translate here
		"整頁式在Internet Explorer 上常出問題, " +
		"因為這是 Internet Explorer 的無名問題，我們無法解決。" +
		"你可能看見一些垃圾，或遇到其他問題。" +
		"我們已警告了你. 如果要轉到 正頁式 請按 好.",

		"Moz-Clipboard" :
		"Unprivileged scripts cannot access Cut/Copy/Paste programatically " +
		"for security reasons.  Click OK to see a technical note at mozilla.org " +
		"which shows you how to allow a script to access the clipboard."
	},

	dialogs: {
		// Common
		"OK"                                                : "OK",
		"Cancel"                                            : "取消",

		"Alignment:"                                        : "Alignment:",
		"Not set"                                           : "Not set",
		"Left"                                              : "左",
		"Right"                                             : "右",
		"Texttop"                                           : "Texttop",
		"Absmiddle"                                         : "Absmiddle",
		"Baseline"                                          : "Baseline",
		"Absbottom"                                         : "Absbottom",
		"Bottom"                                            : "下方",
		"Middle"                                            : "中間",
		"Top"                                               : "上方",

		"Layout"                                            : "Layout",
		"Spacing"                                           : "Spacing",
		"Horizontal:"                                       : "Horizontal:",
		"Horizontal padding"                                : "Horizontal padding",
		"Vertical:"                                         : "Vertical:",
		"Vertical padding"                                  : "Vertical padding",
		"Border thickness:"                                 : "Border thickness:",
		"Leave empty for no border"                         : "Leave empty for no border",

		// Insert Link
		"Insert/Modify Link"                                : "插入/改寫連結",
		"None (use implicit)"                               : "無 (use implicit)",
		"New window (_blank)"                               : "新視窗(_blank)",
		"Same frame (_self)"                                : "本框(_self)",
		"Top frame (_top)"                                  : "上框 (_top)",
		"Other"                                             : "其他",
		"Target:"                                           : "目標框:",
		"Title (tooltip):"                                  : "主題(tooltip):",
		"URL:"                                              : "網址:",
		"You must enter the URL where this link points to"  : "你必須輸入你要連結的網址",
		// Insert Table
		"Insert Table"                                      : "Insert Table",
		"Rows:"                                             : "Rows:",
		"Number of rows"                                    : "Number of rows",
		"Cols:"                                             : "Cols:",
		"Number of columns"                                 : "Number of columns",
		"Width:"                                            : "寬度:",
		"Width of the table"                                : "Width of the table",
		"Percent"                                           : "百分比",
		"Pixels"                                            : "像素",
		"Em"                                                : "Em",
		"Width unit"                                        : "Width unit",
		"Positioning of this table"                         : "Positioning of this table",
		"Cell spacing:"                                     : "Cell spacing:",
		"Space between adjacent cells"                      : "Space between adjacent cells",
		"Cell padding:"                                     : "Cell padding:",
		"Space between content and border in cell"          : "Space between content and border in cell",
		// Insert Image
		"Insert Image"                                      : "插入圖片",
		"Image URL:"                                        : "I圖片網址:",
		"Enter the image URL here"                          : "輸入圖片網址",
		"Preview"                                           : "預覽",
		"Preview the image in a new window"                 : "新視窗中預覽圖片",
		"Alternate text:"                                   : "註解說明:",
		"For browsers that don't support images"            : "瀏覽器不支援圖片",
		"Positioning of this image"                         : "貼上圖片",
		"Image Preview:"                                    : "預覽圖片:"
	}
};
