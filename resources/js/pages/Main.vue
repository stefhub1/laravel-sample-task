<template>
  <div class="board">
    <div
        v-for="(column, columnInd) in columns"
        :key="`column-${columnInd}`"
        class="column"
    >
      <div class="column__header">
        <div class="column__title">
          {{ column.title }}
        </div>
        <div class="column__delete">
          <button class="column__button column__button--red" @click="deleteColumn(column.id, columnInd)">
            &times;
          </button>
        </div>
      </div>
    </div>

    <div
        class="column"
        :class="{'column--adding': addingColumn}"
    >
      <div class="column__content" v-if="addingColumn">
        <div class="form">
          <div class="form__group">
            <label class="form__label">Column Title</label>
            <input class="form__input" v-model="columnTitle"/>
          </div>

          <div class="form__control">
            <button class="column__button column__button--blue" @click="createColumn">Add</button>
            <button class="column__button column__button--red" @click="initColumn">Cancel</button>
          </div>
        </div>
      </div>
      <div class="column__header" v-else>
        <button
            class="column__button column__button--blue column__button--block"
            @click="addingColumn = true"
        >
          Add Column
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Main",
  data() {
    return {
      columns: null, // Columns
      addingColumn: false, // adding column status
      columnTitle: '', // column title model
      selectedCard: null, // selected card
      showCardModal: false, // show card modal status
      dragOptions: { // Vue draggable options
        animation: 200
      },
      isDragging: false,
      delayedDragging: false,
      oldItem: null
    }
  },
  mounted() {
    this.getColumns();
  },
  methods: {
    /**
     * Get columns data
     */
    getColumns() {
      axios.get('/columns')
          .then(r => {
            this.columns = r.data.result;
          });
    },
    /**
     * Create a new column
     */
    createColumn() {
      if (this.columnTitle === '') {
        alert('Column title is required.');
        return;
      }

      axios.post('/columns', {
        title: this.columnTitle
      }).then(r => {
        this.initColumn();

        if (r.data.result === 'success') {
          this.columns = r.data.columns;
        }
      });
    },
    /**
     * Delete column
     */
    deleteColumn(colId, columnInd) {
      axios.delete(`/columns/${colId}`)
          .then(r => {
            this.columns.splice(columnInd, 1);
            if (r.data.result === 'success') {
              this.columns.splice(columnInd, 1);
            }
          });
    },
    initColumn() {
      this.columnTitle = '';
      this.addingColumn = false;
    }
  }
}
</script>

<style scoped>

</style>