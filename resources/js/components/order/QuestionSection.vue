<template>
    <div :style="{ paddingLeft: sectionData.index * 20 + 'px' }" class="que-template">
        <button class="btn btn-danger btn-sm" @click="$emit('deleteQuestion', sectionData.id)">X</button><br />
        <label for="input-1" class="form-label">Питання:</label>
        <input type="text" id="input-1" class="form-control" v-model="sectionData.question">
        <label for="input-2" class="form-label">Питання для опису:</label>
        <input type="text" id="input-2" class="form-control" v-model="sectionData.question_description">
        <div class="input-container">
            <label for="text-input" class="form-label">Додавати до опису:</label>
            <input type="checkbox" id="is_add_description" v-model="sectionData.is_add_description"
                :checked="sectionData.is_add_description == 1">
            <section v-if="sectionData.index !== 0">
                <label for="checkbox" class="form-label">Відображати, коли:</label>
                <select id="selector" class="form-select" v-model="sectionData.selectedOption">
                    <option v-for="(answer, answerIndex) in sectionData.answers" :key="answerIndex"
                        :value="answer.title">
                        {{ answer.title }}
                    </option>
                </select>
            </section>

        </div>
        <div class="question-navigation">
            <button class="btn btn-primary" @click="addAnswers(sectionData, sectionData.index)">+</button>
            <button class="btn btn-secondary" @click="addSubQuestion(sectionData)">?</button>
        </div>
        <div class="answer" v-for="(answer, answerIndex) in sectionData.answers" :key="answerIndex">
            <button class="btn btn-tool" @click="removeAnswer(sectionIndex, answerIndex)">
                <i class="fas fa-times"></i>
            </button>
            <label for="text-input" class="form-label">Відповідь:</label>
            <input type="text" id="text-input" class="form-control" v-model="answer.title">

            <label for="text-input" class="form-label">Відповідь до опису:</label>
            <input type="text" id="text-input" class="form-control" v-model="answer.description">

            <label for="text-input" class="form-label">Коментар:</label>
            <input type="text" id="text-input" class="form-control" v-model="answer.comments">

            <label for="selector" class="form-label">Додати до ціни:</label>
            <select id="selector" class="form-select" v-model="answer.addToPrice">
                <option v-for="(price, priceIndex) in priceList" :key="priceIndex"
                        :value="price.name">
                        {{ price.name }} - {{ price.price }}
                    </option>
            </select>

            
        </div>

        <div v-for="(sub, subIndex) in sectionData.subQuestions" :key="sub.id">
            <question-section :priceList="priceList" :sectionData="sub" :index="sectionData.index"
                @deleteQuestion="handleDeleteSubQuestion(sub.index, sub.id)" @add-sub-question="addSubQuestion">
            </question-section>
        </div>
    </div>
</template>

<script>
export default {
    name: "QuestionSection",
    props: ['sectionData', 'index', 'priceList'],
    methods: {
        addSubQuestion(parentQuestion) {
            const newSubQuestion = {
                id: Date.now(),
                question: "",
                index: parentQuestion.index + 1,
                question_description: "",
                subQuestions: [],
                answers: []
            };
            parentQuestion.subQuestions.push(newSubQuestion);
        },
        addAnswers(parentQuestion) {

            parentQuestion.answers.push({
                title: '', /* ... other answer fields */
                description: '', /* ... other answer fields */
                comments: '', /* ... other answer fields */
                addToPrice: '', /* ... other answer fields */
            });
        },
        removeAnswer(sectionIndex, answerIndex) {
            this.sectionData.answers.splice(answerIndex, 1);
        },
        handleDeleteSubQuestion(sectionIndex, questionId) {
            const section = this.sectionData;
            console.log(section, questionId);
            if (section) {
                const subQuestionIndex = section.subQuestions.findIndex(subQ => +subQ.id === +questionId);
                if (subQuestionIndex !== -1) {
                    section.subQuestions.splice(subQuestionIndex, 1);
                }
            }
        }

    },
}
</script>

<style scoped>
.que-template {
    margin-bottom: 20px;
}

.input-container {
    margin-top: 20px;
}
</style>