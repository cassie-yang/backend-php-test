<template>
  <el-main class="todo-form">
    <el-row v-bind:gutter="20">
      <el-col v-bind:span="18">
        <el-input v-model="newTask"></el-input>
      </el-col>
      <el-col v-bind:span="6" class="el-col--right">
        <el-button v-bind:disabled="!newTask"
                   v-on:click="addTask()"
                   type="primary"
                   plain>
          Add Task
        </el-button>
      </el-col>
    </el-row>
    <el-row v-bind:gutter="20" class="todo-form__list">
      <div v-for="task in tasks" class="todo-form__list-item">
        <el-col v-bind:span="16"
                class="todo-form__list-item__text"
                v-bind:class="[task.completed_at ? 'todo-form__list-item__text--finished' : '']">
          {{task.description}}
        </el-col>
        <el-col v-bind:span="8" class="el-col--right">
          <el-button type="success"
                     v-on:click="markTaskDone(task.id)"
                     v-if="!task.completed_at"
                     plain>
            Done
          </el-button>
          <el-button type="warning"
                     v-on:click="markTaskUndone(task.id)"
                     v-if="task.completed_at"
                     plain>
            Undone
          </el-button>
          <el-button type="danger"
                     v-on:click="deleteTask(task.id)"
                     plain>
            Delete
          </el-button>
        </el-col>
      </div>
    </el-row>
  </el-main>
</template>
<script>
  import axios     from 'axios';

  export default {
    data() {
      return {
        newTask: '',
        tasks: null,
      };
    },
    mounted() {
      this.getTasks();
    },
    methods: {
      getTasks() {
        axios.get('/todos/json')
                .then(response => {
                  this.tasks = response.data;
                })
                .catch(error => {
                  console.log(error);
                });
      },
      addTask() {
        axios.post('/todo/add', {description: this.newTask})
                .then(response => {
                 this.getTasks();
                  this.newTask = '';
                })
                .catch(error => {
                  console.log(error);
                });
      },
      deleteTask(id) {
        axios.post('/todo/delete/' + id)
                .then(response => {
                  this.getTasks();
                })
                .catch(error => {
                  console.log(error);
                });
      },
      markTaskDone(id) {
        axios.post('/todo/done/' + id)
                .then(response => {
                  this.getTasks();
                })
                .catch(error => {
                  console.log(error);
                });
      },
      markTaskUndone(id) {
        axios.post('/todo/undone/' + id)
                .then(response => {
                  this.getTasks();
                })
                .catch(error => {
                  console.log(error);
                });
      }
    }
  };
</script>
<style lang="scss" scoped>
  .el-col--right {
    text-align: right;
  }

  .todo-form {
    &__list {
      margin-top: 30px;
      border-top: solid 2px #ddd;
      border-bottom: solid 1px #ddd;
    }

    &__list-item {
      border-bottom: solid 1px #ddd;
      display: block;
      overflow: hidden;
      padding-top: 5px;
      padding-bottom: 5px;

      &__text {
        padding: 5px 0;
        font-size: 17px;

        &--finished {
          text-decoration: line-through;
        }
      }
    }
  }
</style>
