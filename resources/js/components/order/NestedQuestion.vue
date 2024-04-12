<template>
    <div class="card-body">
        <div class="card-header">{{ question.question }} </div>
        <div class="question card-body">
            <select v-model="selectedAnswer"
                @change="checkSubquestionVisibility(question.subQuestions, question, selectedAnswer)"
                class="form-select">
                <option v-for="answer in question.answers" :value="answer.title" :key="answer.title">
                    {{ answer.title }}
                </option>
                <option value="custom">Власна відповідь</option>
            </select>

            <input v-if="selectedAnswer === 'custom'" v-model="customAnswer" type="text" class="form-control"
                placeholder="Власна відповідь">
        </div>

        <div v-if="isShowSubQuestions" class="card">
            <NestedQuestion @priceChange="(a, b, c, d) => this.$emit('priceChange', a, b, c, d)"
                v-for="subquestion in question.subQuestions" :key="subquestion.id" :question="subquestion" />
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
            isShowSubQuestions: false,
            customAnswer: '', // Store custom answer
        }
    },
    methods: {
        checkSubquestionVisibility(subQuestion, question, answerTitle) {
            this.handlePriceChange(true, subQuestion, question, answerTitle)
        },
        handlePriceChange(isRun, subQuestion, question, answerTitle) {
            if (!isRun) return
            if (subQuestion.length > 0) {
                this.isShowSubQuestions = subQuestion[0].selectedOption === answerTitle;
            }
            const answerOption = this.question.answers.find((item) => item.title === answerTitle).addToPrice
            this.$emit('priceChange', answerOption, question.question, question, this.customAnswer)
        },
    },
    computed: {
        shouldShowSubQuestions() {
            this.isShowSubQuestions = question.selectedOption === this.selectedAnswer;
        }
    }
}
</script>