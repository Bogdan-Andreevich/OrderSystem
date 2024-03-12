<template>
    <div class="unit-replacement-section">
        <select v-model="selectedUnitId" class="form-control form-control-sm" @change="fetchPriceList(); fetchQuestion();">
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
        <button class="btn btn-primary btn-sm" @click="handleClick">?</button>
        <div class="card">
            <div class="card-body">
                <div class="input-container">

                    <section class="que-template" v-for="(sectionData, index) in sections" :key="index">
                        <QuestionSection @deleteQuestion="handleDeleteQuestion" :priceList="priceList" :sectionData="sectionData" :index="1" />
                    </section>
                    <button @click="saveData" class="btn btn-primary">Зберегти</button>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
import QuestionSection from './QuestionSection.vue'
export default {
    name: "DetailQuestion",
    components: {
        QuestionSection
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
        handleClearData(){
            this.selectedUnitId = null;
            this.selectedUnitCopyId = null;
            this.unitTypes = [];
            this.sections = [];
            this.sectionsCopy = [];
            this.priceList = [];
        },
        async fetchPriceList() {
            try {
                const response = await this.axios.get(`http://localhost/api/price/rows/${this.selectedUnitId}`);
                this.priceList = response.data.categories;

            } catch (error) {
                console.error('Error copying categories:', error);
            }
        },
        handleCopyQuestion() {
            this.sections = [...this.sections, ...this.sectionsCopy]
        },
        saveData() {
            const payload = this.sections;

            for (const question of payload) {
                if (question.isNew) { // Check for existing ID
                    this.createNewQuestionData(question);
                } else {
                    this.updateQuestionData(question);
                }
                this.handleClearData()
            }
        },

        createNewQuestionData(payload) {
            this.axios.post('http://localhost/api/questions', { ...payload, subQuestions: JSON.stringify(payload.subQuestions), answers: JSON.stringify(payload.answers), typeDocId: this.selectedUnitId.toString() })
                .then(() => {
                    alert("Questions created!")
                })
                .catch((e) => {
                    console.log("ERROR: ", e);
                });
        },

        updateQuestionData(payload) {
            const questionId = payload.id;
            this.axios.put(`http://localhost/api/questions/${questionId}`, { ...payload, subQuestions: JSON.stringify(payload.subQuestions), answers: JSON.stringify(payload.answers), typeDocId: this.selectedUnitId.toString() })
                .then(() => {
                    alert("Questions updated!")
                })
                .catch(() => {
                    console.log("ERROR: ", e);
                });
        },


        handleDeleteQuestion(targetId) {
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
                    is_add_description: false,
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
                    
                    const response = await this.axios.get(`http://localhost/api/questionsTypeById/${this.selectedUnitId}`);
                    this.sections = response.data;
                } catch (error) {
                    console.error("Error fetching questions:", error);
                }
            } else {
                this.questions = []; // Clear questions, for example
            }
        },
        async fetchQuestionCopy() {
            if (this.selectedUnitCopyId) { // Check if a unit is selected
                try {
                    const response = await this.axios.get(`http://localhost/api/questionsTypeById/${this.selectedUnitCopyId}`);
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
        this.axios.get("http://localhost/api/typeoforders").then((response) => {
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
