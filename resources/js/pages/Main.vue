<template>
  <div class="board">
    
  </div>
</template>

<script>
export default {
  name: "Main",
  data() {
    return {
      columns: null, // Columns
      addingColumn: false, // adding column status
      columnTitle: '', // column title model
      selectedCard: null, // selected card
      showCardModal: false, // show card modal status
      validations: {
        addColumn: false
      },
      dragOptions: { // Vue draggable options
        animation: 200
      },
      isDragging: false,
      delayedDragging: false,
      oldItem: null
    }
  }
}
</script>

<style scoped>

</style>