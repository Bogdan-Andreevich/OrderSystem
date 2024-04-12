<template>
    <div class="unit-replacement-section">
        <select v-model="selectedUnitId" class="form-control form-control-sm"
            @change="fetchPriceList(); fetchQuestion();">
            <option v-for="unitType in unitTypes" :key="unitType.id" :value="unitType.id">
                {{ unitType.name }}
            </option>
        </select>

        <section class="mt-3">
            <button @click="handleCopyQuestion" class="btn btn-sm btn-primary">копіювати питання з іншого ТЗ</button>
            <select v-model="selectedUnitCopyId" class="form-control form-control-sm" @change="fetchQuestionCopy">
                <option v-for="unitType in unitTypes" :key="unitType.id" :value="unitType.id">
                    {{ unitType.name }}
                </option>
            </select>
        </section>
    </div>
    <section v-if="selectedUnitId">
        <button class="btn btn-primary btn-sm" @click="handleClick">Додати питання</button>
        <div class="card">
            <div class="card-body">
                <div class="input-container">
                    <draggable :list="sections" group="sections" @start="dragStart" @end="dragEnd" item-key="index">
                        <template #item="{ element, index }">
                            <div>
                                <QuestionSection @deleteQuestion="handleDeleteQuestion" :priceList="priceList"
                                    :sectionData="element" :index="1" />
                            </div>
                        </template>
                    </draggable>
                    <button @click="saveData" class="btn btn-primary">Зберегти</button>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
import QuestionSection from './QuestionSection.vue'
import draggable from 'vuedraggable'

export default {
    name: "DetailQuestion",
    components: {
        QuestionSection,
        draggable
    },
    data() {
        return {
            selectedUnitId: null,
            selectedUnitCopyId: null,
            unitTypes: [],
            sections: [],
            sectionsCopy: [],
            priceList: [],
        };
    },
    methods: {
        handleClearData() {
            this.selectedUnitId = null;
            this.selectedUnitCopyId = null;
            this.sections = [];
            this.sectionsCopy = [];
            this.priceList = [];
        },
        async fetchPriceList() {
            try {
                const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/price/rows/${this.selectedUnitId}`);
                this.priceList = response.data.categories;

            } catch (error) {
                console.error('Error copying categories:', error);
            }
        },
        handleCopyQuestion() {
            this.sections = [...this.sections, ...this.sectionsCopy.map(this.handleCopyAnswer)]
        },
        handleCopyAnswer(item) {
            let answers = item.answers
            if (item.answers) {
                answers = answers.map((itemAnswer) => ({ ...itemAnswer, description: itemAnswer.description === "" ? itemAnswer.title : itemAnswer.description }))
            }

            if (item.subQuestions) {
                this.handleCopyAnswer(item.subQuestions)
            }

            return { ...item, isNew: true, answers }
        },
        saveData() {
            let i = 0;
            const payload = this.sections.map((item, index) => ({ ...item, index: ++i }));

            for (const question of payload) {
                if (question.isNew) { // Check for existing ID
                    this.createNewQuestionData(question);
                } else {
                    this.updateQuestionData(question);
                }
            }
            alert("Complete!")
            this.handleClearData()
        },

        createNewQuestionData(payload) {
            this.axios.post('http://crm-test.san-sanych.in.ua/api/questions', { ...payload, subQuestions: JSON.stringify(payload.subQuestions), answers: JSON.stringify(payload.answers), typeDocId: this.selectedUnitId.toString() })
                // .then(() => {
                //     alert("Questions created!")
                // })
                .catch((e) => {
                    console.log("ERROR: ", e);
                });
        },

        updateQuestionData(payload) {
            const questionId = payload.id;
            this.axios.put(`http://crm-test.san-sanych.in.ua/api/questions/${questionId}`, { ...payload, subQuestions: JSON.stringify(payload.subQuestions), answers: JSON.stringify(payload.answers), typeDocId: this.selectedUnitId.toString() })
                // .then(() => {
                //     alert("Questions updated!")
                // })
                .catch(() => {
                    console.log("ERROR: ", e);
                });
        },

        handleDeleteQuestion(targetId) {
            if (!confirm("Ви точно хочете видалити питання?(Всі субпитання також будуть видалені)")) {
                return;  // Abort deletion process if user cancels
            }

            this.removeQuestion(targetId);

            let newSections = JSON.parse(JSON.stringify(this.sections)); // True Deep Copy

            const recursiveDelete = (questions) => {
                for (let i = questions.length - 1; i >= 0; i--) {
                    if (+questions[i].id === +targetId) {  // Consider options from above
                        questions.splice(i, 1);
                        return true;
                    }
                    if (questions[i].subQuestions.length > 0) {
                        if (recursiveDelete(questions[i].subQuestions)) return true;
                    }
                }
                return false;
            }

            if (recursiveDelete(newSections)) {
                this.sections = newSections;
            } else {
                console.error("Question not found for deletion.");
            }
        },
        handleClick() {
            this.sections.push(
                {
                    question: '', // Initialize with empty values
                    question_description: '',
                    is_add_description: true,
                    selectedOption: '',
                    answers: [],
                    subQuestions: [],
                    index: 0,
                    id: 1,
                    isNew: true
                });
        },
        async fetchQuestion() {
            if (this.selectedUnitId) { // Check if a unit is selected
                try {

                    const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/questionsTypeById/${this.selectedUnitId}`);
                    this.sections = response.data.sort((a,b)=>a.index - b.index).map((item)=>({...item, index:0}));
                } catch (error) {
                    console.error("Error fetching questions:", error);
                }
            } else {
                this.questions = []; // Clear questions, for example
            }
        },
        async removeQuestion(id) {
                try {
                await this.axios.delete(`http://crm-test.san-sanych.in.ua/api/questions/${id}`);
                 
                } catch (error) {
                    console.error("Error fetching questions:", error);
                }
        
        },
        async fetchQuestionCopy() {
            if (this.selectedUnitCopyId) { // Check if a unit is selected
                try {
                    const response = await this.axios.get(`http://crm-test.san-sanych.in.ua/api/questionsTypeById/${this.selectedUnitCopyId}`);
                    this.sectionsCopy = response.data;
                } catch (error) {
                    console.error("Error fetching questions:", error);
                }
            } else {
                this.questions = []; // Clear questions, for example
            }
        }
    },
    mounted() {
        this.axios.get("http://crm-test.san-sanych.in.ua/api/typeoforders").then((response) => {
            this.unitTypes = response.data;
        });

    }
};
</script>

<style scoped>
.que-template {
    margin-top: 20px;
}

/* Add your styling here, if necessary */
</style>
