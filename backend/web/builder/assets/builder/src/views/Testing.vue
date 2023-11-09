<template>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<div class="icl-editor__wrap">
      <Sidebar/>
      <Preview/>
    </div>
</body>
</html>
</template>

<script>
import Toolbar from "@/components/ui/Toolbar"
import Sidebar from "@/components/ui/Sidebar"
import Preview from "@/components/ui/Preview"

export default {
  name: 'builder',
  components: {
    Toolbar,
    Sidebar,
    Preview
  },
}
</script>

