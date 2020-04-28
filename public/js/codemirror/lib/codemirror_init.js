var codemirrorInstance = [];
var foundtextareasarr = document.getElementsByClassName('codemirror');
for(var i = 0; foundtextareasarr[i]; ++i) {
  codemirrorInstance[i] = CodeMirror.fromTextArea(foundtextareasarr[i], {
    lineNumbers: true,
    autoCloseTags: true,
    viewportMargin: Infinity,
    theme:'darcula',
    mode: 'htmlmixed',
    tags: {
      style: [["type", /^text\/(x-)?scss$/, "text/x-scss"],
        [null, null, "css"]],
      custom: [[null, null, "customMode"]]
    },
    lineWrapping: true,
    extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]


  });
}