<template>
    <div class="card">
        <div class="card-body">
            <div class="card-header">{{ question.question }} </div>
            <div class="question card-body"> <select v-model="selectedAnswer"
                    @change="checkSubquestionVisibility(question.subQuestions)" class="form-select">
                    <option v-for="answer in question.answers" :value="answer.title" :key="answer.title">
                        {{ answer.title }}
                    </option>
                </select>
            </div>

            <div v-if="isShowSubQuestions" class="card">
                <NestedQuestion v-for="subquestion in question.subQuestions" :key="subquestion.id"
                    :question="subquestion" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "NestedQuestion",
    props: ['question'],
    data() {
        return {
            selectedAnswer: null,
            isShowSubQuestions: false
        }
    },
    methods: {
        checkSubquestionVisibility(subQuestion) {
            this.isShowSubQuestions = subQuestion[0].selectedOption === this.selectedAnswer;
        }
    },
    computed: {
        shouldShowSubQuestions() {
            console.log('====================================');
            console.log(question.selectedOption, this.selectedAnswer);
            console.log('====================================');
            this.isShowSubQuestions = question.selectedOption === this.selectedAnswer;
        }
    }
}
</script>