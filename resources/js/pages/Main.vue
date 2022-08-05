<template>
  <div class="board">
    <!-- Card modal -->
    <modal
        name="card-modal"
        :adaptive="true"
        :draggable="true"
        :resizable="true"
        class="modal form"
    >
      <div class="modal__header" v-if="cardModalShow">
        <div class="modal__title">
          {{ editingCard && editingCard.sort_number < 0 ? 'Add' : 'Edit' }} Card
        </div>
      </div>

      <div class="modal__body" v-if="cardModalShow">
        <div class="form__group">
          <label class="form__label">Card Title:</label>
          <input type="text" placeholder="Card Title" class="form__input" v-model="editingCard.title"/>
        </div>

        <div class="form__group">
          <label class="form__label">Card Description:</label>
          <textarea class="form__input" rows="5" v-model="editingCard.description"/>
        </div>
      </div>

      <div class="modal__footer" v-if="cardModalShow">
        <div class="form__actions">
          <button
              type="button"
              class="column__button column__button--blue"
              @click="saveCard()"
              :disabled="!editingCard.title || !editingCard.description"
          >
            Save
          </button>
          <button type="button" class="column__button column__button--red" @click="closeCardModal()">Cancel</button>
        </div>
      </div>
    </modal>

    <div class="board__float">
      <button type="button" class="column__button column__button--green" @click="exportDB">
        Export DB
      </button>
    </div>

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
          <button type="button" class="column__button column__button--red" @click="deleteColumn(column.id, columnInd)">
            &times;
          </button>
        </div>
      </div>

      <draggable
          v-model="column.cards"
          v-bind="dragOptions"
          :move="onMoveCard"
          @start="isDragging=true"
          @end="onMoveEnd"
          class="column__content"
          :id="`column-${columnInd}`"
          group="column"
      >
        <div
            v-for="(card, cardInd) in column.cards"
            :key="`card-${cardInd}`"
            class="card"
        >
          <div class="card__title" @click="showCardModal(cardInd, column)">
            {{ card.title }}
          </div>
        </div>
      </draggable>

      <div class="column__footer">
        <button
            type="button"
            class="column__button column__button--hover column__button--block"
            @click="showCardModal(-1, column)"
        >
          + Add Card
        </button>
      </div>
    </div>

    <div
        class="column"
        :class="{'column--adding': addingColumn}"
    >
      <div class="column__content" v-if="addingColumn">
        <div class="form">
          <div class="form__group">
            <input type="text" placeholder="Column Title" class="form__input" v-model="columnTitle" @keyup.enter="createColumn"/>
          </div>

          <div class="form__actions">
            <button type="button" class="column__button column__button--blue" @click="createColumn">Add</button>
            <button type="button" class="column__button column__button--red" @click="initColumn">Cancel</button>
          </div>
        </div>
      </div>

      <div class="column__header" v-else>
        <button
            type="button"
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
import draggable from 'vuedraggable';

export default {
  name: 'Main',
  components: {
    draggable
  },
  data() {
    return {
      columns: null, // Columns
      addingColumn: false, // adding column status
      columnTitle: '', // column title model
      editingCard: null, // selected card
      cardModalShow: false, // show card modal status
      dragOptions: { // Vue draggable options
        animation: 200
      },
      isDragging: false,
      delayedDragging: false,
      prevCard: null // previous card before moving
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
            if (r.data.result === 'success') {
              this.columns.splice(columnInd, 1);
            }
          });
    },
    /**
     * Init add column
     */
    initColumn() {
      this.columnTitle = '';
      this.addingColumn = false;
    },
    /**
     * Get column index
     * @param id
     * @returns {*}
     */
    getColumnIndex(id) {
      return this.columns.findIndex((item) => item.id === id);
    },
    /**
     * Moving card event
     * @param e
     * @returns {boolean}
     */
    onMoveCard(e) {
      this.prevCard = Object.assign({}, e.draggedContext.element);

      return true;
    },
    /**
     * Card move end event
     * @param e
     */
    onMoveEnd(e) {
      const oldColumnIndex = this.getColumnIndex(this.prevCard.column_id);
      const columnId = e.to.getAttribute('id');
      const columnIndex = parseInt(columnId.split('-')[1]);
      const oldColumn = Object.assign({}, this.columns[oldColumnIndex]);
      const newColumn = Object.assign({}, this.columns[columnIndex]);

      this.columns[oldColumnIndex].cards.map((el, ind) => {
        el.sort_number = ind;
        el.column_id = oldColumn.id;

        return el;
      });

      this.$nextTick(() => {
        this.columns[columnIndex].cards.map((el, ind) => {
          el.sort_number = ind;
          el.column_id = newColumn.id;

          return el;
        });

        this.$nextTick(() => {
          const cardIndex = this.columns[columnIndex].cards.findIndex((item) => item.id === this.prevCard.id);

          this.updateCard(this.columns[columnIndex].cards[cardIndex], columnIndex);
        });
      });
    },
    /**
     * show card modal
     *
     * @param cardIndex
     * @param column
     */
    showCardModal(cardIndex, column) {
      this.cardModalShow = true;

      if (cardIndex < 0) {
        this.editingCard = Object.assign({}, {
          id: 0,
          column_id: column.id,
          title: '',
          description: '',
          sort_number: cardIndex
        });
      } else {
        this.editingCard = Object.assign({}, column.cards[cardIndex]);
        this.editingCard.sort_number = cardIndex;
      }

      this.$modal.show('card-modal');
    },
    /**
     * Save card data
     */
    saveCard() {
      const columnInd = this.getColumnIndex(this.editingCard.column_id);

      if (this.editingCard.sort_number < 0 || this.editingCard.id === 0) {
        // Create
        const cardData = Object.assign({}, {
          ...this.editingCard,
          sort_number: this.columns[columnInd].cards.length
        });

        this.columns[columnInd].cards.push(cardData);

        this.createCard(cardData);
      } else {
        // Update
        this.columns[columnInd].cards[this.editingCard.sort_number] = Object.assign({}, this.editingCard);

        this.updateCard(this.columns[columnInd].cards[this.editingCard.sort_number], columnInd);
      }
    },
    /**
     * Create Card
     *
     * @param card
     */
    createCard(card) {
      axios.post('/cards', {
        ...card
      }).then(r => {
        if (r.data.result === 'success') {
          this.columns = r.data.columns;
        }

        if (this.cardModalShow) {
          this.closeCardModal();
        }
      });
    },

    /**
     * Update card data
     *
     * @param card
     * @param columnInd
     */
    updateCard(card, columnInd) {
      axios.put(`/cards/${card.id}`, {
        ...card
      }).then(r => {
        if (r.data.result === 'success') {
          this.updateCardSortNumbers(columnInd);
        }
      });
    },

    /**
     * Update card's sort numbers
     *
     * @param columnInd
     */
    updateCardSortNumbers(columnInd) {
      const column = this.columns[columnInd];

      axios.put(`/columns/${column.id}`, {
        cards: column.cards
      }).then(r => {
        if (this.cardModalShow) {
          this.closeCardModal();
        }
      });
    },

    /**
     * close card modal
     */
    closeCardModal() {
      this.cardModalShow = false;

      this.$modal.hide('card-modal');
    },

    exportDB() {
      window.open('/export-db', '_blank');
    }
  },
  watch: {
    isDragging(newValue) {
      if (newValue) {
        this.delayedDragging = true;
        return;
      }
      this.$nextTick(() => {
        this.delayedDragging = false;
      });
    }
  }
}
</script>

<style scoped>

</style>