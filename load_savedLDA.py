# later on, load trained model from file
model =  models.LdaModel.load('lda.model')

# print all topics
model.show_topics(topics=200, topn=20)

# print topic 28
model.print_topic(109, topn=20)

# another way
for i in range(0, model.num_topics-1):
    print model.print_topic(i)

# and another way, only prints top words
for t in range(0, model.num_topics-1):
    print 'topic {}: '.format(t) + ', '.join([v[1] for v in model.show_topic(t, 20)])
